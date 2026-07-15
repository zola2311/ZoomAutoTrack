<?php

namespace App\Filament\Resources\InventoryItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InventoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        TextInput::make('part_number')
                            ->label('Part Number')
                            ->maxLength(100),

                        TextInput::make('brand')
                            ->maxLength(100),

                        Select::make('category')
                            ->options([
                                'engine'      => 'Engine',
                                'brakes'      => 'Brakes',
                                'electrical'  => 'Electrical',
                                'suspension'  => 'Suspension',
                                'body'        => 'Body',
                                'fluids'      => 'Fluids & Lubricants',
                                'tires'       => 'Tires',
                                'other'       => 'Other',
                            ])
                            ->searchable()->required(),

                        TextInput::make('location')
                            ->helperText('Shelf / bin location in the store'),
                    ]),

                Section::make('Pricing')
                    ->columns(2)
                    ->schema([
                        TextInput::make('unit_cost')
                            ->label('Unit Cost')
                            ->numeric()
                            ->prefix('ETB')
                            ->default(0)
                            ->required(),

                        TextInput::make('selling_price')
                            ->label('Selling Price')
                            ->numeric()
                            ->prefix('ETB')
                            ->default(0)
                            ->required(),
                    ]),

                Section::make('Stock')
                    ->columns(2)
                    ->schema([
                        TextInput::make('quantity_on_hand')
                            ->label('Quantity on Hand')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->disabledOn('edit')
                            ->helperText('Set only on creation. Use "Adjust Stock" afterward to change this.'),

                        TextInput::make('minimum_stock')
                            ->label('Minimum Stock (reorder point)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ]),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }
}
