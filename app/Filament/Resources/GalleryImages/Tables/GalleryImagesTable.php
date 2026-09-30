<?php

namespace App\Filament\Resources\GalleryImages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GalleryImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),

                ImageColumn::make('image')
                    ->label('Photo')
                    ->height(50),

                TextColumn::make('caption')
                    ->label('Caption')
                    ->searchable()
                    ->placeholder('—'),

                IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Visible')
                    ->placeholder('All Images')
                    ->trueLabel('Visible Only')
                    ->falseLabel('Hidden Only'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('gallery_images.delete')),
                ]),
            ]);
    }
}
