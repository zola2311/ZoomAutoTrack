<?php

namespace App\Filament\Resources\Quotations\Tables;

use App\Models\Customer;
use App\Models\JobCard;
use App\Models\Quotation;
use App\Models\Vehicle;
use App\Notifications\QuotationEstimateReadyNotification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuotationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quotation_number')
                    ->label('Ref #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(['customer_name', 'phone', 'email'])
                    ->description(fn (Quotation $record) => $record->phone),

                TextColumn::make('vehicle_display_name')
                    ->label('Vehicle')
                    ->searchable(['make', 'model', 'plate_number']),

                TextColumn::make('service_category')
                    ->label('Category')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('urgency')
                    ->label('Urgency')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'urgent_asap' => 'danger',
                        'within_week' => 'warning',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'urgent_asap' => '🔥 Urgent',
                        'within_week' => '📅 This Week',
                        default       => 'Flexible',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'          => 'warning',
                        'in_review'        => 'info',
                        'quoted'           => 'success',
                        'accepted'         => 'success',
                        'declined'         => 'danger',
                        'converted_to_job' => 'purple',
                        default            => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'          => 'Pending',
                        'in_review'        => 'In Review',
                        'quoted'           => 'Quotation Sent',
                        'accepted'         => 'Accepted',
                        'declined'         => 'Declined',
                        'converted_to_job' => 'Work Order Opened',
                        default            => ucfirst($state),
                    })
                    ->sortable(),

                TextColumn::make('estimated_total')
                    ->label('Estimate (ETB)')
                    ->money('ETB')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'          => 'Pending Assessment',
                        'in_review'        => 'In Review / Diagnostics',
                        'quoted'           => 'Quotation Sent',
                        'accepted'         => 'Accepted',
                        'declined'         => 'Declined',
                        'converted_to_job' => 'Converted to Work Order',
                    ]),

                SelectFilter::make('urgency')
                    ->options([
                        'urgent_asap' => 'Urgent (ASAP)',
                        'within_week' => 'Within This Week',
                        'flexible'    => 'Flexible',
                    ]),

                SelectFilter::make('service_category')
                    ->options([
                        'Engine & Diagnostics'      => 'Engine & Diagnostics',
                        'Brakes & ABS'              => 'Brakes & ABS',
                        'Suspension & Steering'     => 'Suspension & Steering',
                        'Transmission & Drivetrain' => 'Transmission & Drivetrain',
                        'Electrical, Battery & AC'  => 'Electrical, Battery & AC',
                        'Bodywork & Collision'      => 'Bodywork & Collision',
                        'Pre-Purchase Inspection'   => 'Pre-Purchase Inspection',
                        'Other / Problem Not Listed' => 'Other / Problem Not Listed',
                    ]),
            ])
            ->actions([
                ViewAction::make(),

                EditAction::make()
                    ->label('Assess & Price'),

                // Download PDF Action
                Action::make('downloadPdf')
                    ->label('PDF Estimate')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('gray')
                    ->url(fn (Quotation $record) => route('quotation.pdf', $record->quotation_number))
                    ->openUrlInNewTab(),

                // Send Official Quotation Email to Customer Action
                Action::make('sendQuotationEmail')
                    ->label('Send Email Quote')
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Quotation $record) => "Send Official Quotation to {$record->customer_name}")
                    ->modalDescription(fn (Quotation $record) => "Are you ready to dispatch the diagnostic assessment and estimated cost breakdown of " . number_format($record->estimated_total, 2) . " ETB to {$record->email}?")
                    ->modalSubmitActionLabel('Yes, Send Quotation Email')
                    ->visible(fn (Quotation $record) => $record->estimated_total > 0 || !empty($record->diagnostic_assessment))
                    ->action(function (Quotation $record) {
                        try {
                            $record->update([
                                'status'                 => 'quoted',
                                'quoted_by'              => auth()->id(),
                                'quoted_at'              => now(),
                                'response_email_sent_at' => now(),
                                'valid_until'            => $record->valid_until ?? now()->addDays(14),
                            ]);

                            \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                                ->notify(new QuotationEstimateReadyNotification($record));

                            Notification::make()
                                ->title('Quotation Dispatched!')
                                ->body("Official report & estimate successfully sent to {$record->email}.")
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
                    ->visible(fn (Quotation $record) => $record->status !== 'converted_to_job')
                    ->action(function (Quotation $record) {
                        try {
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
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
