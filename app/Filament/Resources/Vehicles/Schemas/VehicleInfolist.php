<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\Vehicle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Owner')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer.full_name')
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

                        TextEntry::make('qr_code')
                            ->label('QR Code')
                            ->copyable()
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
