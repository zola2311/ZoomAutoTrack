<?php

namespace App\Filament\Resources\InventoryItems\Schemas;

use App\Models\InventoryItem;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InventoryItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('part_number')->label('Part #')->placeholder('-'),
                        TextEntry::make('brand')->placeholder('-'),
                        TextEntry::make('category')->badge()->placeholder('-'),
                        TextEntry::make('location')->placeholder('-'),
                        TextEntry::make('branch.name')->label('Branch')->placeholder('-'),
                    ]),

                Section::make('Pricing')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('unit_cost')->label('Unit Cost')->money('ETB'),
                        TextEntry::make('selling_price')->label('Selling Price')->money('ETB'),
                    ]),

                Section::make('Stock Status')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('quantity_on_hand')
                            ->label('Quantity on Hand')
                            ->badge()
                            ->color(fn (InventoryItem $record) => $record->quantity_on_hand <= $record->minimum_stock
                                ? 'danger'
                                : 'success'),

                        TextEntry::make('minimum_stock')
                            ->label('Minimum Stock'),
                    ]),

                Section::make('Supplier Prices')
                    ->schema([
                        RepeatableEntry::make('supplierPrices')
                            ->label('')
                            ->schema([
                                TextEntry::make('supplier.name')->label('Supplier'),
                                TextEntry::make('supplier_part_number')->label('Supplier Part #')->placeholder('-'),
                                TextEntry::make('last_price')->money('ETB'),
                                TextEntry::make('available_quantity')->label('Available Qty')->placeholder('-'),
                                TextEntry::make('last_checked_at')->dateTime()->placeholder('-'),
                            ])
                            ->columns(5),
                    ])
                    ->visible(fn (InventoryItem $record) => $record->supplierPrices->isNotEmpty()),

                Section::make('Stock Movement History')
                    ->schema([
                        RepeatableEntry::make('stockMovements')
                            ->label('')
                            ->schema([
                                TextEntry::make('type')->badge(),
                                TextEntry::make('quantity'),
                                TextEntry::make('supplier.name')->label('Supplier')->placeholder('-'),
                                TextEntry::make('creator.name')->label('By')->placeholder('-'),
                                TextEntry::make('created_at')->dateTime()->label('Date'),
                                TextEntry::make('notes')->placeholder('-'),
                            ])
                            ->columns(6),
                    ])
                    ->visible(fn (InventoryItem $record) => $record->stockMovements->isNotEmpty()),

                Section::make('Record Info')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')->dateTime()->placeholder('-'),
                        TextEntry::make('updated_at')->dateTime()->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (InventoryItem $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
