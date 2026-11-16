<?php

namespace App\Filament\Resources\Quotations\Schemas;

use App\Models\Quotation;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuotationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Overview & Status Header
                Section::make('Quotation Overview')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('quotation_number')
                            ->label('Quotation #')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('status')
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
                                'pending'          => 'Pending Assessment',
                                'in_review'        => 'In Review / Diagnostics',
                                'quoted'           => 'Quotation Sent to Client',
                                'accepted'         => 'Accepted by Client',
                                'declined'         => 'Declined by Client',
                                'converted_to_job' => 'Converted to Work Order',
                                default            => ucfirst($state),
                            }),

                        TextEntry::make('urgency')
                            ->label('Urgency')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'urgent_asap' => 'danger',
                                'within_week' => 'primary',
                                default       => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'urgent_asap' => '🔥 URGENT (ASAP)',
                                'within_week' => '📅 Within This Week',
                                default       => '⏱️ Flexible Timeline',
                            }),

                        TextEntry::make('created_at')
                            ->label('Requested On')
                            ->dateTime('M d, Y h:i A'),
                    ]),

                // 2. Customer & Vehicle Specifications
                Section::make('Customer & Vehicle Specifications')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label('Customer Name')
                            ->weight('semibold'),

                        TextEntry::make('phone')
                            ->label('Phone Number')
                            ->url(fn ($record) => "tel:{$record->phone}"),

                        TextEntry::make('email')
                            ->label('Email Address')
                            ->url(fn ($record) => "mailto:{$record->email}"),

                        TextEntry::make('city')
                            ->label('Location')
                            ->placeholder('Addis Ababa'),

                        TextEntry::make('vehicle_display_name')
                            ->label('Vehicle')
                            ->weight('bold')
                            ->columnSpan(2),

                        TextEntry::make('plate_number')
                            ->label('Plate Number')
                            ->placeholder('Not recorded'),

                        TextEntry::make('mileage')
                            ->label('Current Mileage')
                            ->formatStateUsing(fn ($state) => $state ? number_format($state) . ' km' : 'Not recorded'),

                        TextEntry::make('transmission')
                            ->label('Transmission')
                            ->placeholder('-'),

                        TextEntry::make('fuel_type')
                            ->label('Power / Fuel')
                            ->placeholder('-'),

                        TextEntry::make('vin')
                            ->label('VIN / Chassis')
                            ->placeholder('Not recorded')
                            ->columnSpan(2),
                    ]),

                // 3. Defect & Diagnostic Questionnaire
                Section::make('Customer Questionnaire & Symptoms')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('service_category')
                            ->label('Category')
                            ->weight('bold')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('drivable_status')
                            ->label('Drivability')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'drivable'           => 'success',
                                'drivable_with_care' => 'warning',
                                'not_drivable'       => 'danger',
                                default              => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'drivable'           => '✅ Fully Drivable',
                                'drivable_with_care' => '⚠️ Drives with Caution',
                                'not_drivable'       => '⛔ Not Drivable (Needs Towing)',
                                default              => ucfirst($state),
                            }),

                        TextEntry::make('preferred_date')
                            ->label('Preferred Service Date')
                            ->date('M d, Y')
                            ->placeholder('Flexible'),

                        TextEntry::make('symptoms')
                            ->label('Observed Symptoms')
                            ->badge()
                            ->columnSpanFull()
                            ->placeholder('No specific symptom checkboxes selected'),

                        TextEntry::make('description')
                            ->label('Problem Description from Customer')
                            ->placeholder('No written text provided (refer to voice note or symptoms)')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'italic text-base']),
                    ]),

                // 4. Voice Note Recording
                Section::make('🎙️ Customer Voice Note Recording')
                    ->description('Audio explanation recorded by the customer describing the issue or defect sound.')
                    ->schema([
                        ViewEntry::make('voice_note')
                            ->hiddenLabel()
                            ->view('filament.infolists.quotation-voice-note')
                            ->columnSpanFull(),
                    ]),

                // 5. Uploaded Defect Pictures
                Section::make('📸 Uploaded Defect & Vehicle Pictures')
                    ->description('High-resolution photos submitted by the customer showing vehicle damage, warning lights, or mechanical leaks.')
                    ->schema([
                        ViewEntry::make('defect_photos')
                            ->hiddenLabel()
                            ->view('filament.infolists.quotation-defect-photos')
                            ->columnSpanFull(),
                    ]),

                // 5. Official Assessment & Work Scope
                Section::make('🛠️ Workshop Assessment & Scope of Work')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('diagnostic_assessment')
                            ->label('Diagnostic Findings & Work to be Performed')
                            ->columnSpanFull()
                            ->placeholder('No assessment recorded yet. Click Edit or use the action button to formulate the quotation report.'),

                        TextEntry::make('estimated_duration')
                            ->label('Estimated Repair Time')
                            ->placeholder('-'),

                        TextEntry::make('valid_until')
                            ->label('Quotation Valid Until')
                            ->date('M d, Y')
                            ->placeholder('-'),
                    ]),

                // 6. Cost Estimation Summary
                Section::make('💰 Cost Estimation Breakdown')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('estimated_labor_total')
                            ->label('Labor Total')
                            ->money('ETB'),

                        TextEntry::make('estimated_parts_total')
                            ->label('Parts Total')
                            ->money('ETB'),

                        TextEntry::make('estimated_discount')
                            ->label('Discount')
                            ->money('ETB'),

                        TextEntry::make('estimated_total')
                            ->label('Estimated Total Investment')
                            ->money('ETB')
                            ->weight('bold')
                            ->size('lg')
                            ->color('success'),

                        TextEntry::make('quotedBy.name')
                            ->label('Quoted By')
                            ->placeholder('Not assigned yet'),

                        TextEntry::make('quoted_at')
                            ->label('Quotation Issued At')
                            ->dateTime('M d, Y h:i A')
                            ->placeholder('-'),

                        TextEntry::make('response_email_sent_at')
                            ->label('Customer Email Sent At')
                            ->dateTime('M d, Y h:i A')
                            ->placeholder('Not sent yet'),
                    ]),
            ]);
    }
}
