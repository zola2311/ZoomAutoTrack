<?php

namespace App\Filament\Resources\JobCards\Pages;

use App\Models\Invoice;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Filament\Resources\JobCards\JobCardResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditJobCard extends EditRecord
{
    protected static string $resource = JobCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ✅ ADDED: Mark as Completed action (same as ViewJobCard)
            Action::make('markCompleted')
                ->label('Mark as Completed')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => ! in_array($this->record->status, ['completed', 'cancelled'])
                    && ($this->record->services()->exists() || $this->record->partsUsed()->exists())
                )
                ->requiresConfirmation()
                ->action(function () {
                    $incompleteCount = $this->record->services()
                        ->where('is_completed', false)
                        ->where('status', '!=', 'cancelled')
                        ->count();

                    if ($incompleteCount > 0) {
                        $this->record->services()
                            ->where('is_completed', false)
                            ->where('status', '!=', 'cancelled')
                            ->each(function ($service) {
                                $service->update([
                                    'is_completed' => true,
                                    'status' => 'completed',
                                    'completed_at' => now(),
                                ]);
                            });
                    }

                    $this->record->update(['status' => 'completed', 'completed_at' => now()]);
                    $this->refreshFormData(['status', 'completed_at']);

                    if ($incompleteCount > 0) {
                        Notification::make()
                            ->title('Job card marked as completed')
                            ->body("{$incompleteCount} labor service(s) were automatically marked as completed too.")
                            ->warning()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Job card marked as completed')
                            ->success()
                            ->send();
                    }
                }),

            // ✅ ADDED: Combined Generate/Update Invoice action
            Action::make('invoice')
                ->label(fn () => $this->record->invoice ? 'Update Invoice' : 'Generate Invoice')
                ->icon(fn () => $this->record->invoice ? 'heroicon-o-arrow-path' : 'heroicon-o-document-currency-dollar')
                ->color(fn () => $this->record->invoice ? 'warning' : 'success')
                ->visible(fn () => $this->record->invoice
                    || $this->record->services()->exists()
                    || $this->record->partsUsed()->exists()
                )
                ->requiresConfirmation()
                ->modalHeading(fn () => $this->record->invoice ? 'Update Invoice' : 'Generate Invoice')
                ->modalDescription(fn () => $this->record->invoice
                    ? 'This will update the existing invoice with the latest services and parts from this job card.'
                    : 'This will create an invoice from this job card\'s services and parts.'
                )
                ->modalSubmitActionLabel(fn () => $this->record->invoice ? 'Yes, Update Invoice' : 'Yes, Generate Invoice')
                ->action(function () {
                    if ($this->record->invoice) {
                        $this->record->invoice->resyncFromJobCard();

                        Notification::make()
                            ->title("Invoice {$this->record->invoice->invoice_number} updated successfully!")
                            ->success()
                            ->send();

                        $this->redirect(InvoiceResource::getUrl('view', ['record' => $this->record->invoice]));
                    } else {
                        $invoice = Invoice::createFromJobCard($this->record);

                        Notification::make()
                            ->title("Invoice {$invoice->invoice_number} generated successfully!")
                            ->success()
                            ->send();

                        $this->redirect(InvoiceResource::getUrl('view', ['record' => $invoice]));
                    }
                }),

            // ✅ ADDED: View Invoice action
            Action::make('viewInvoice')
                ->label('View Invoice')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->visible(fn () => (bool) $this->record->invoice)
                ->url(fn () => $this->record->invoice
                    ? InvoiceResource::getUrl('view', ['record' => $this->record->invoice])
                    : null),

            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
    protected function afterSave(): void
    {
        if ($this->record->status !== 'completed') {
            return;
        }

        $incompleteCount = $this->record->services()
            ->where('is_completed', false)
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($incompleteCount > 0) {
            $this->record->services()
                ->where('is_completed', false)
                ->where('status', '!=', 'cancelled')
                ->each(function ($service) {
                    $service->update([
                        'is_completed' => true,
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                });

            Notification::make()
                ->title('Job card marked as completed')
                ->body("{$incompleteCount} labor service(s) were automatically marked as completed too.")
                ->warning()
                ->send();
        }
    }
}
