<?php

namespace App\Filament\Resources\JobCards\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class JobCardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('job_number')
                    ->label('Job #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('vehicle.plate_number')
                    ->label('Plate')
                    ->searchable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('vehicle.make')
                    ->label('Vehicle')
                    ->formatStateUsing(fn ($record) => $record->vehicle?->make . ' ' . $record->vehicle?->model)
                    ->searchable(),

                TextColumn::make('mechanic.name')
                    ->label('Mechanic')
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'       => 'gray',
                        'in_progress'   => 'info',
                        'quality_check' => 'warning',
                        'completed'     => 'success',
                        'cancelled'     => 'danger',
                        default         => 'gray',
                    }),

                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'normal' => 'gray',
                        'urgent' => 'warning',
                        'vip'    => 'danger',
                        default  => 'gray',
                    }),

                TextColumn::make('mileage_at_checkin')
                    ->label('Mileage')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable(),

                TextColumn::make('checked_in_at')
                    ->label('Checked In')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('estimated_completion_at')
                    ->label('Est. Completion')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('checked_in_at', 'desc')
            ->filters([

                SelectFilter::make('status')
                    ->options([
                        'pending'       => 'Pending',
                        'in_progress'   => 'In Progress',
                        'quality_check' => 'Quality Check',
                        'completed'     => 'Completed',
                        'cancelled'     => 'Cancelled',
                    ]),

                SelectFilter::make('priority')
                    ->options([
                        'normal' => 'Normal',
                        'urgent' => 'Urgent',
                        'vip'    => 'VIP',
                    ]),

                SelectFilter::make('mechanic_id')
                    ->label('Mechanic')
                    ->relationship('mechanic', 'name'),

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

