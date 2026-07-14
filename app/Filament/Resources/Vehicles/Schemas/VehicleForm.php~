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
                Section::make('Owner')
                    ->columns(2)
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn () => Customer::query()
                                ->orderBy('full_name')
                                ->get()
                                ->mapWithKeys(fn ($customer) => [
                                    $customer->id => $customer->full_name . ' - ' . $customer->phone,
                                ])
                                ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Vehicle Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('plate_number')
                            ->label('Plate Number')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('make')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('model')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('year')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue((int) date('Y') + 1),

                        TextInput::make('color')
                            ->maxLength(50),

                        TextInput::make('current_mileage')
                            ->label('Current Mileage')
                            ->numeric()
                            ->minValue(0),
                    ]),

                Section::make('Identifiers')
                    ->columns(2)
                    ->schema([
                        TextInput::make('chassis_number')
                            ->label('Chassis Number')
                            ->maxLength(100),

                        TextInput::make('engine_number')
                            ->label('Engine Number')
                            ->maxLength(100),

                        Select::make('fuel_type')
                            ->options([
                                'petrol' => 'Petrol',
                                'diesel' => 'Diesel',
                                'hybrid' => 'Hybrid',
                                'electric' => 'Electric',
                            ])
                            ->searchable(),

                        Select::make('transmission')
                            ->options([
                                'manual' => 'Manual',
                                'automatic' => 'Automatic',
                            ])
                            ->searchable(),
                    ]),
            ]);
    }
}
