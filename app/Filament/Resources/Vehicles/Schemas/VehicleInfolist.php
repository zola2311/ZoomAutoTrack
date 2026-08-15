<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\Vehicle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Filament\Infolists\Components\ViewEntry;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Owner')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer.display_name')
                            ->label('Customer'),

                        TextEntry::make('customer.phone')
                            ->label('Phone')
                            ->placeholder('-'),

                        TextEntry::make('branch.name')
                            ->label('Branch')
                            ->placeholder('-'),
                    ]),

                Section::make('Vehicle Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('plate_number')
                            ->label('Plate Number')
                            ->badge()
                            ->color('success'),

                        TextEntry::make('make'),

                        TextEntry::make('model'),

                        TextEntry::make('year')
                            ->numeric()
                            ->placeholder('-'),

                        TextEntry::make('color')
                            ->placeholder('-'),

                        TextEntry::make('current_mileage')
                            ->label('Current Mileage')
                            ->numeric()
                            ->suffix(' km')
                            ->placeholder('-'),
                    ]),

                Section::make('Maintenance Schedule')
                    ->columns(3)
                    ->schema([

                        TextEntry::make('next_service_status')
                            ->label('Status')
                            ->state(fn (Vehicle $record) => $record->nextServiceDue()['is_due'] ? 'Due Now' : 'On Track')
                            ->badge()
                            ->color(fn (Vehicle $record) => $record->nextServiceDue()['is_due'] ? 'danger' : 'success'),

                        TextEntry::make('next_service_mileage')
                            ->label('Due At Mileage')
                            ->state(fn (Vehicle $record) => number_format($record->nextServiceDue()['due_mileage']) . ' km')
                            ->color(fn (Vehicle $record) => $record->nextServiceDue()['km_remaining'] <= 0 ? 'danger' : 'gray'),

                        TextEntry::make('next_service_date')
                            ->label('Due By Date')
                            ->state(fn (Vehicle $record) => $record->nextServiceDue()['due_date']->format('d M Y'))
                            ->color(fn (Vehicle $record) => $record->nextServiceDue()['days_remaining'] <= 0 ? 'danger' : 'gray'),

                        TextEntry::make('km_remaining')
                            ->label('Kilometers Remaining')
                            ->state(fn (Vehicle $record) => $record->nextServiceDue()['km_remaining'] > 0
                                ? number_format($record->nextServiceDue()['km_remaining']) . ' km'
                                : 'Overdue'
                            ),

                        TextEntry::make('days_remaining')
                            ->label('Days Remaining')
                            ->state(fn (Vehicle $record) => $record->nextServiceDue()['days_remaining'] > 0
                                ? $record->nextServiceDue()['days_remaining'] . ' days'
                                : 'Overdue'
                            ),

                        TextEntry::make('service_interval_km')
                            ->label('Service Interval')
                            ->state(fn (Vehicle $record) => number_format($record->service_interval_km) . ' km / ' . $record->service_interval_months . ' months'),

                    ]),

                Section::make('Digital Passport')
                    ->columns(2)
                    ->schema([
                        ViewEntry::make('qr_image')
                            ->hiddenLabel()
                            ->view('filament.infolists.vehicle-qr-code')
                            ->columnSpan(1),

                        TextEntry::make('qr_code')
                            ->label('Passport Link')
                            ->state(fn (Vehicle $record) => $record->passportUrl())
                            ->url(fn (Vehicle $record) => $record->passportUrl(), shouldOpenInNewTab: true)
                            ->copyable()
                            ->placeholder('-')
                            ->columnSpan(1),
                    ]),

                Section::make('Identifiers')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('chassis_number')
                            ->label('Chassis Number')
                            ->placeholder('-'),

                        TextEntry::make('engine_number')
                            ->label('Engine Number')
                            ->placeholder('-'),

                        TextEntry::make('fuel_type')
                            ->label('Fuel Type')
                            ->badge()
                            ->placeholder('-'),

                        TextEntry::make('transmission')
                            ->badge()
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
                            ->visible(fn (Vehicle $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
