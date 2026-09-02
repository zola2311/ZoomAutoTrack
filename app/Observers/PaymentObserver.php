<?php

namespace App\Observers;

use App\Models\Payment;
use App\Notifications\InvoicePaid;
use App\Notifications\PartialPaymentReceived;

class PaymentObserver
{
    // 1 loyalty point per 100 ETB paid.
    protected const POINTS_PER_ETB = 100;

    public function created(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
        $this->awardPoints($payment);
        $this->notifyCustomer($payment);
    }

    public function updated(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    public function deleted(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    public function restored(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    public function forceDeleted(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    protected function recalculateInvoice(Payment $payment): void
    {
        if ($payment->invoice) {
            $payment->invoice->recalculateFromPayments();
        }
    }

    protected function awardPoints(Payment $payment): void
    {
        $customer = $payment->invoice?->customer;

        if (! $customer) {
            return;
        }

        $points = (int) floor($payment->amount / self::POINTS_PER_ETB);

        if ($points > 0) {
            $customer->increment('loyalty_points', $points);
        }
    }

    protected function notifyCustomer(Payment $payment): void
    {
        $invoice = $payment->invoice?->fresh();

        if (! $invoice || ! $invoice->customer || ! $invoice->customer->email) {
            return;
        }

        if ($invoice->status === 'paid') {
            $invoice->customer->notify(new InvoicePaid($invoice, (float) $payment->amount));
        } elseif ($invoice->status === 'partial') {
            $invoice->customer->notify(new PartialPaymentReceived($invoice, (float) $payment->amount));
        }
    }
}
