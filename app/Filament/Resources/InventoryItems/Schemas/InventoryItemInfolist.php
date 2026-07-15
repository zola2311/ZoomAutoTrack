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
                // --- Section 1: Item Details ---
                Section::make('Item Details')
                    ->columnSpanFull() // 🌟 FIX: Spans full width of the page
                    ->icon('heroicon-o-cube')
                    ->columns(3) // Increased to 3 columns for a tighter, cleaner layout
                    ->schema([
                        TextEntry::make('name')
                            ->weight('bold'),

                        TextEntry::make('part_number')
                            ->label('Part #')
                            ->placeholder('-')
                            ->copyable()
                            ->icon('heroicon-m-clipboard-document'),

                        TextEntry::make('brand')
                            ->placeholder('-'),

                        TextEntry::make('category')
                            ->badge()
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('location')
                            ->icon('heroicon-m-map-pin')
                            ->placeholder('-'),

                        TextEntry::make('branch.name')
                            ->label('Branch')
                            ->placeholder('-'),
                    ]),

                // --- Section 2: Pricing & Stock (Grouped side-by-side using grids) ---
                Section::make('Pricing & Stock')
                    ->columnSpanFull() // 🌟 FIX: Spans full width
                    ->columns(4)       // Beautiful 4-column row for financials and stock quantities
                    ->schema([
                        TextEntry::make('unit_cost')
                            ->label('Unit Cost')
                            ->money('ETB')
                            ->weight('semibold'),

                        TextEntry::make('selling_price')
                            ->label('Selling Price')
                            ->money('ETB')
                            ->weight('semibold')
                            ->color('success'),

                        TextEntry::make('quantity_on_hand')
                            ->label('Quantity on Hand')
                            ->badge()
                            ->color(fn (InventoryItem $record) => $record->quantity_on_hand <= $record->minimum_stock
                                ? 'danger'
                                : 'success'
                            ),

                        TextEntry::make('minimum_stock')
                            ->label('Minimum Stock')
                            ->weight('medium'),
                    ]),

                // --- Section 3: Supplier Prices ---
                Section::make('Supplier Prices')
                    ->columnSpanFull() // 🌟 FIX: Spans full width
                    ->icon('heroicon-o-truck')
                    ->schema([
                        RepeatableEntry::make('supplierPrices')
                            ->label('')
                            ->schema([
                                TextEntry::make('supplier.name')
                                    ->label('Supplier')
                                    ->weight('semibold'),
                                TextEntry::make('supplier_part_number')->label('Supplier Part #')->placeholder('-'),
                                TextEntry::make('last_price')->money('ETB'),
                                TextEntry::make('available_quantity')->label('Available Qty')->placeholder('-'),
                                TextEntry::make('last_checked_at')->dateTime()->placeholder('-'),
                            ])
                            ->columns(5),
                    ])
                    ->visible(fn (InventoryItem $record) => $record->supplierPrices->isNotEmpty()),

                // --- Section 4: Stock Movement History ---
                Section::make('Stock Movement History')
                    ->columnSpanFull() // 🌟 FIX: Spans full width
                    ->icon('heroicon-o-arrows-up-down')
                    ->schema([
                        RepeatableEntry::make('stockMovements')
                            ->label('')
                            ->schema([
                                TextEntry::make('type')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'purchase', 'return', 'adjustment_add', 'initial' => 'success',
                                        'usage', 'adjustment_remove', 'damaged', 'expired' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),
                                TextEntry::make('quantity')
                                    ->weight('bold'),
                                TextEntry::make('supplier.name')->label('Supplier')->placeholder('-'),
                                TextEntry::make('creator.name')->label('By')->placeholder('-'),
                                TextEntry::make('created_at')->dateTime()->label('Date'),
                                TextEntry::make('notes')->placeholder('-'),
                            ])
                            ->columns(6),
                    ])
                    ->visible(fn (InventoryItem $record) => $record->stockMovements->isNotEmpty()),

                // --- Section 5: Record Info ---
                Section::make('Record Info')
                    ->columnSpanFull() // 🌟 FIX: Spans full width
                    ->icon('heroicon-o-information-circle')
                    ->columns(3)
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
