<?php

namespace App\Filament\Resources\Quotations\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Models\Customer;
use App\Models\JobCard;
use App\Models\Quotation;
use App\Models\Vehicle;
use App\Notifications\QuotationEstimateReadyNotification;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewQuotation extends ViewRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Download Official Quotation PDF
            Action::make('downloadPdf')
                ->label('Download PDF Estimate')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->url(fn () => route('quotation.pdf', $this->record->quotation_number))
                ->openUrlInNewTab(),

            // Send Official Quotation Email to Customer
            Action::make('sendEmail')
                ->label('Send Official Quotation Email')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(fn () => "Send Official Quotation to {$this->record->customer_name}")
                ->modalDescription(fn () => "Are you ready to dispatch the diagnostic assessment and cost breakdown (" . number_format($this->record->estimated_total, 2) . " ETB) to {$this->record->email}?")
                ->modalSubmitActionLabel('Dispatch Quotation Email')
                ->visible(fn () => $this->record->estimated_total > 0 || !empty($this->record->diagnostic_assessment))
                ->action(function () {
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
                            ->title('Quotation Dispatched!')
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

            // Convert to Job Card Action
            Action::make('convertToJobCard')
                ->label('Convert to Work Order')
                ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Create Workshop Job Card from this Quotation')
                ->modalDescription('This will link or create the customer & vehicle, and open a new Job Card in Workshop Operations.')
                ->visible(fn () => $this->record->status !== 'converted_to_job')
                ->action(function () {
                    try {
                        $record = $this->record;

                        // Find or create customer
                        $customer = $record->customer;
                        if (!$customer) {
                            $customer = Customer::create([
                                'branch_id' => $record->branch_id ?? 1,
                                'full_name' => $record->customer_name,
                                'phone'     => $record->phone,
                                'email'     => $record->email,
                                'type'      => 'individual',
                                'address'   => $record->city,
                            ]);
                            $record->update(['customer_id' => $customer->id]);
                        }

                        // Find or create vehicle
                        $vehicle = $record->vehicle;
                        if (!$vehicle) {
                            $vehicle = Vehicle::create([
                                'customer_id'        => $customer->id,
                                'make'               => $record->make,
                                'model'              => $record->model,
                                'year'               => $record->year ?? date('Y'),
                                'plate_number'       => $record->plate_number ?: 'TBD-' . rand(1000, 9999),
                                'current_mileage'    => $record->mileage ?? 0,
                                'transmission'       => $record->transmission ?? 'Automatic',
                                'fuel_type'          => $record->fuel_type ?? 'Petrol',
                                'chassis_number'     => $record->vin,
                            ]);
                            $record->update(['vehicle_id' => $vehicle->id]);
                        }

                        // Create Job Card
                        $jobCard = JobCard::create([
                            'branch_id'           => $record->branch_id ?? 1,
                            'customer_id'         => $customer->id,
                            'vehicle_id'          => $vehicle->id,
                            'service_advisor_id'  => auth()->id(),
                            'mileage_at_checkin'  => $record->mileage ?? $vehicle->current_mileage,
                            'customer_complaint'  => "Quotation Inquiry #{$record->quotation_number}: {$record->service_category}\nSymptoms: " . (!empty($record->symptoms) ? implode(', ', $record->symptoms) : 'None') . "\nDescription: {$record->description}",
                            'mechanic_notes'      => $record->diagnostic_assessment,
                            'status'              => 'pending',
                            'priority'            => $record->urgency === 'urgent_asap' ? 'high' : 'normal',
                            'checked_in_at'       => now(),
                        ]);

                        $record->update(['status' => 'converted_to_job']);

                        Notification::make()
                            ->title('Job Card Created!')
                            ->body("Job Card #{$jobCard->job_number} created successfully from quotation.")
                            ->success()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('openJob')
                                    ->button()
                                    ->url("/admin/job-cards/{$jobCard->id}"),
                            ])
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Conversion Error')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            EditAction::make()
                ->label('Edit / Price Estimate'),
        ];
    }
}
