<?php

namespace App\Filament\Resources\InventoryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InventoryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('part_number')
                    ->label('Part #')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('brand')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('category')
                    ->badge(),

                TextColumn::make('quantity_on_hand')
                    ->label('Qty on Hand')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->quantity_on_hand <= $record->minimum_stock
                        ? 'danger'
                        : 'success'),

                TextColumn::make('minimum_stock')
                    ->label('Min. Stock')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('unit_cost')
                    ->money('ETB')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('selling_price')
                    ->money('ETB')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

            ])
            ->defaultSort('name')
            ->filters([

                SelectFilter::make('category')
                    ->options([
                        'engine'     => 'Engine',
                        'brakes'     => 'Brakes',
                        'electrical' => 'Electrical',
                        'suspension' => 'Suspension',
                        'body'       => 'Body',
                        'fluids'     => 'Fluids & Lubricants',
                        'tires'      => 'Tires',
                        'other'      => 'Other',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active'),

                TrashedFilter::make(),

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
