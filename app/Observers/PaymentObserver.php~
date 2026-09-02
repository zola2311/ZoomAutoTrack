<?php

namespace App\Observers;

use App\Models\Payment;
use App\Notifications\InvoicePaid;
use App\Notifications\PartialPaymentReceived;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
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
