<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Invoice;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Overview')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label('Invoice #')
                            ->badge()
                            ->color('success'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'unpaid'  => 'danger',
                                'partial' => 'warning',
                                'paid'    => 'success',
                                'void'    => 'gray',
                                default   => 'gray',
                            }),

                        TextEntry::make('jobCard.job_number')
                            ->label('Job Card'),

                        TextEntry::make('branch.name')
                            ->label('Branch')
                            ->placeholder('-'),
                    ]),

                Section::make('Customer')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer.full_name')
                            ->label('Customer'),

                        TextEntry::make('customer.phone')
                            ->label('Phone')
                            ->placeholder('-'),
                    ]),

                Section::make('Line Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('item_type')
                                    ->badge(),

                                TextEntry::make('description'),

                                TextEntry::make('quantity'),

                                TextEntry::make('unit_price')
                                    ->money('ETB'),

                                TextEntry::make('total')
                                    ->money('ETB'),
                            ])
                            ->columns(5),
                    ]),

                Section::make('Totals')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('subtotal')->money('ETB'),
                        TextEntry::make('discount')->money('ETB'),
                        TextEntry::make('tax')->money('ETB'),
                        TextEntry::make('total')
                            ->money('ETB')
                            ->weight('bold'),
                        TextEntry::make('paid_amount')
                            ->label('Paid')
                            ->money('ETB')
                            ->color('success'),
                        TextEntry::make('balance')
                            ->money('ETB')
                            ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                    ]),

                Section::make('Payments')
                    ->schema([
                        RepeatableEntry::make('payments')
                            ->label('')
                            ->schema([
                                TextEntry::make('amount')
                                    ->money('ETB'),

                                TextEntry::make('method')
                                    ->badge(),

                                TextEntry::make('reference_number')
                                    ->placeholder('-'),

                                TextEntry::make('paid_at')
                                    ->dateTime()
                                    ->placeholder('-'),

                                TextEntry::make('receiver.name')
                                    ->label('Received By')
                                    ->placeholder('-'),
                            ])
                            ->columns(5),
                    ])
                    ->visible(fn (Invoice $record) => $record->payments->isNotEmpty()),

                Section::make('Dates & Record Info')
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('issued_at')->dateTime()->placeholder('-'),
                        TextEntry::make('due_at')->dateTime()->placeholder('-'),
                        TextEntry::make('created_at')->dateTime()->placeholder('-'),
                        TextEntry::make('updated_at')->dateTime()->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (Invoice $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
