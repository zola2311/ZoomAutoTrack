<?php

namespace App\Filament\Resources\JobCards\Schemas;

use App\Models\JobCard;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobCardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Job Overview')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('job_number')
                            ->label('Job #')
                            ->badge()
                            ->color('success'),

                        TextEntry::make('branch.name')
                            ->label('Branch')
                            ->placeholder('-'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending'       => 'gray',
                                'in_progress'   => 'info',
                                'quality_check' => 'warning',
                                'completed'     => 'success',
                                'cancelled'     => 'danger',
                                default         => 'gray',
                            }),

                        TextEntry::make('priority')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'normal' => 'gray',
                                'urgent' => 'warning',
                                'vip'    => 'danger',
                                default  => 'gray',
                            }),
                    ]),

                Section::make('Customer & Vehicle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer.full_name')
                            ->label('Customer'),

                        TextEntry::make('customer.phone')
                            ->label('Phone')
                            ->placeholder('-'),

                        TextEntry::make('vehicle.plate_number')
                            ->label('Plate')
                            ->badge(),

                        TextEntry::make('vehicle.make')
                            ->label('Vehicle')
                            ->formatStateUsing(fn ($record) => $record->vehicle?->make . ' ' . $record->vehicle?->model),

                        TextEntry::make('mileage_at_checkin')
                            ->label('Mileage at Check-in')
                            ->numeric()
                            ->suffix(' km')
                            ->placeholder('-'),

                        TextEntry::make('fuel_level')
                            ->placeholder('-'),
                    ]),

                Section::make('Staff')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('serviceAdvisor.name')
                            ->label('Service Advisor')
                            ->placeholder('-'),

                        TextEntry::make('mechanic.name')
                            ->label('Mechanic')
                            ->placeholder('-'),
                    ]),

                Section::make('Complaint & Notes')
                    ->schema([
                        TextEntry::make('customer_complaint')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('mechanic_notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Timeline')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('checked_in_at')
                            ->label('Checked In')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('estimated_completion_at')
                            ->label('Est. Completion')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('completed_at')
                            ->label('Completed')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('delivered_at')
                            ->label('Delivered')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                Section::make('Record Info')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (JobCard $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
