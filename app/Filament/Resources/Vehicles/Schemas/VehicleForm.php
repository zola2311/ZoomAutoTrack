<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Add Vehicle')
                    ->columnSpanFull() // 🌟 FIX: Forces the section to stretch full width
                    ->columns(2)       // Keeps your form fields structured nicely inside
                    ->schema([
                        // Row 1: Customer - FULL WIDTH
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn () => Customer::query()
                                ->orderByRaw('COALESCE(company_name, full_name)')
                                ->get()
                                ->mapWithKeys(fn ($customer) => [
                                    $customer->id => $customer->display_name . ' - ' . $customer->phone,
                                ])
                                ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),

                        // Row 2: Plate Number + Make
                        TextInput::make('plate_number')
                            ->label('Plate Number')
                            ->required()
                            ->maxLength(20)
                            ->unique(table: 'vehicles', column: 'plate_number', ignoreRecord: true)
                            ->placeholder('AA 3-45678')
                            ->helperText('Ethiopian format: AA 3-45678')
                            ->columnSpan(1),

                        TextInput::make('make')
                            ->label('Make')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g. Toyota, Nissan, BMW')
                            ->columnSpan(1),

                        // Row 3: Model + Year
                        TextInput::make('model')
                            ->label('Model')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g. Corolla, Patrol, X5')
                            ->columnSpan(1),

                        TextInput::make('year')
                            ->label('Year')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue((int) date('Y') + 1)
                            ->placeholder('e.g. 2020')
                            ->columnSpan(1),

                        // Row 4: Color + Current Mileage
                        TextInput::make('color')
                            ->label('Color')
                            ->maxLength(50)
                            ->placeholder('e.g. White, Black, Silver')
                            ->columnSpan(1),

                        TextInput::make('current_mileage')
                            ->label('Current Mileage (km)')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('e.g. 45000')
                            ->columnSpan(1),

                        // Row 5: Chassis Number + Engine Number
                        TextInput::make('chassis_number')
                            ->label('Chassis Number')
                            ->maxLength(100)
                            ->placeholder('e.g. JTDBR32E5R0123456')
                            ->unique(table: 'vehicles', column: 'chassis_number', ignoreRecord: true)
                            ->columnSpan(1),

                        TextInput::make('engine_number')
                            ->label('Engine Number')
                            ->maxLength(100)
                            ->placeholder('e.g. 2ZR-FE123456')
                            ->unique(table: 'vehicles', column: 'engine_number', ignoreRecord: true)
                            ->columnSpan(1),

                        // Row 6: Fuel Type + Transmission
                        Select::make('fuel_type')
                            ->label('Fuel Type')
                            ->options([
                                'petrol' => 'Petrol',
                                'diesel' => 'Diesel',
                                'hybrid' => 'Hybrid',
                                'electric' => 'Electric',
                            ])
                            ->searchable()
                            ->placeholder('Select fuel type')
                            ->columnSpan(1),

                        Select::make('transmission')
                            ->label('Transmission')
                            ->options([
                                'manual' => 'Manual',
                                'automatic' => 'Automatic',
                            ])
                            ->searchable()
                            ->placeholder('Select transmission type')
                            ->columnSpan(1),
                        TextInput::make('service_interval_km')
                            ->label('Service Interval (km)')
                            ->numeric()
                            ->default(5000)
                            ->minValue(500)
                            ->suffix('km')
                            ->helperText('How often this vehicle should be serviced, by distance'),

                        TextInput::make('service_interval_months')
                            ->label('Service Interval (months)')
                            ->numeric()
                            ->default(6)
                            ->minValue(1)
                            ->maxValue(24)
                            ->suffix('months')
                            ->helperText('How often this vehicle should be serviced, by time'),
                    ])
                    ->compact(),
            ]);
    }
}
