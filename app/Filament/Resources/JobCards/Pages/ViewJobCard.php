<?php


namespace App\Filament\Resources\JobCards\Pages;

use App\Models\Invoice;
use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

// Fix the namespace route here by adding "\JobCards"
use App\Filament\Resources\JobCards\JobCardResource;

class ViewJobCard extends ViewRecord
{
    protected static string $resource = JobCardResource::class;

    protected function getHeaderActions(): array
    {
        // ... rest of your action code remains the same
        return [
            Action::make('generateInvoice')
                ->label('Generate Invoice')
                ->icon('heroicon-o-document-currency-dollar')
                ->color('success')
                ->visible(fn () => ! $this->record->invoice)
                ->requiresConfirmation()
                ->modalDescription('This will create an invoice from this job card\'s services and parts.')
                ->action(function () {
                    $invoice = Invoice::createFromJobCard($this->record);

                    Notification::make()
                        ->title("Invoice {$invoice->invoice_number} generated")
                        ->success()
                        ->send();

                    $this->redirect(InvoiceResource::getUrl('view', ['record' => $invoice]));
                }),

            Action::make('viewInvoice')
                ->label('View Invoice')
                ->icon('heroicon-o-document-text')
                ->visible(fn () => (bool) $this->record->invoice)
                ->url(fn () => $this->record->invoice
                    ? InvoiceResource::getUrl('view', ['record' => $this->record->invoice])
                    : null),

            EditAction::make(),
        ];
    }
}
