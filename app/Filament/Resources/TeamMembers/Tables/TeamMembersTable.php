<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),

                ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->height(40),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Visible')
                    ->placeholder('All Members')
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
                        ->authorize(fn () => auth()->user()->can('team_members.delete')),
                ]),
            ]);
    }
}
