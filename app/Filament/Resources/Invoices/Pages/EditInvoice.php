<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['subtotal'] ??= 0;
        $data['total'] ??= 0;
        $data['paid_amount'] ??= 0;
        $data['balance'] ??= 0;

        return $data;
    }

    protected function afterSave(): void
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
