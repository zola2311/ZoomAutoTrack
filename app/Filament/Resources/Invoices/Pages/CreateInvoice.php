<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\JobCard;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['job_card_id'])) {
            $jobCard = JobCard::find($data['job_card_id']);

            if ($jobCard) {
                $data['branch_id'] = $jobCard->branch_id;
                $data['customer_id'] = $jobCard->customer_id;
            }
        } else {
            $data['branch_id'] ??= auth()->user()->branch_id ?? 1;
        }

        $data['subtotal'] ??= 0;
        $data['total'] ??= 0;
        $data['paid_amount'] ??= 0;
        $data['balance'] ??= 0;

        return $data;
    }
    protected function afterCreate(): void
    {
        $this->recalculateTotals();
    }

    protected function recalculateTotals(): void
    {
        $invoice = $this->record->fresh('items');

        $subtotal = $invoice->items->sum('total');
        $total = $subtotal - $invoice->discount + $invoice->tax;

        $invoice->update([
            'subtotal' => $subtotal,
            'total'    => $total,
            'balance'  => $total - $invoice->paid_amount,
        ]);
    }

}
