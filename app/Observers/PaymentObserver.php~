<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    /**
     * Recalculate the invoice totals when a payment changes.
     */
    protected function recalculateInvoice(Payment $payment): void
    {
        if ($payment->invoice) {
            $payment->invoice->recalculateFromPayments();
        }
    }
}
