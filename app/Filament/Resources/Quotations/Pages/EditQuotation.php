<?php

namespace App\Filament\Resources\Quotations\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Notifications\QuotationEstimateReadyNotification;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),

            Action::make('sendEmail')
                ->label('Save & Send Quotation Email')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(fn () => "Save & Send Quotation to {$this->record->customer_name}")
                ->modalDescription(fn () => "This will save any pending changes and immediately send the official quotation email to {$this->record->email}.")
                ->action(function () {
                    $this->save();

                    try {
                        $this->record->update([
                            'status'                 => 'quoted',
                            'quoted_by'              => auth()->id(),
                            'quoted_at'              => now(),
                            'response_email_sent_at' => now(),
                            'valid_until'            => $this->record->valid_until ?? now()->addDays(14),
                        ]);

                        \Illuminate\Support\Facades\Notification::route('mail', $this->record->email)
                            ->notify(new QuotationEstimateReadyNotification($this->record));

                        Notification::make()
                            ->title('Quotation Saved & Dispatched!')
                            ->body("Official report & estimate successfully sent to {$this->record->email}.")
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Delivery Error')
                            ->body('Failed to dispatch email: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            DeleteAction::make(),
        ];
    }
}
