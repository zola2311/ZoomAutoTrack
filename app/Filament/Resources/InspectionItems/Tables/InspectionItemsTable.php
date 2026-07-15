<?php

namespace App\Filament\Resources\InspectionItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InspectionItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->toggleable()
                    ->width('50px'),

                TextColumn::make('name')
                    ->label('Item Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : '—'),

                TextColumn::make('input_type')
                    ->label('Input Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pass_fail' => 'success',
                        'pass_fail_warning' => 'warning',
                        'numeric' => 'info',
                        'text' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pass_fail' => 'Pass/Fail',
                        'pass_fail_warning' => 'Pass/Fail/Warning',
                        'numeric' => 'Numeric',
                        'text' => 'Text',
                        default => $state,
                    }),

                IconColumn::make('requires_photo')
                    ->label('📷')
                    ->boolean()
                    ->trueIcon('heroicon-o-camera')
                    ->falseIcon('heroicon-o-minus'),

                IconColumn::make('requires_voice_note')
                    ->label('🎤')
                    ->boolean()
                    ->trueIcon('heroicon-o-microphone')
                    ->falseIcon('heroicon-o-minus'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'engine' => 'Engine',
                        'brakes' => 'Brakes',
                        'suspension' => 'Suspension',
                        'electrical' => 'Electrical',
                        'body' => 'Body',
                        'interior' => 'Interior',
                        'exterior' => 'Exterior',
                        'tires' => 'Tires & Wheels',
                        'fluids' => 'Fluids & Lubricants',
                        'cooling' => 'Cooling System',
                        'exhaust' => 'Exhaust System',
                        'transmission' => 'Transmission',
                        'steering' => 'Steering',
                        'battery' => 'Battery',
                        'lighting' => 'Lighting',
                        'other' => 'Other',
                    ]),

                SelectFilter::make('input_type')
                    ->label('Input Type')
                    ->options([
                        'pass_fail' => 'Pass/Fail',
                        'pass_fail_warning' => 'Pass/Fail/Warning',
                        'numeric' => 'Numeric',
                        'text' => 'Text',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All Items')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),

                TernaryFilter::make('requires_photo')
                    ->label('Requires Photo')
                    ->placeholder('All Items')
                    ->trueLabel('With Photo')
                    ->falseLabel('Without Photo'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
