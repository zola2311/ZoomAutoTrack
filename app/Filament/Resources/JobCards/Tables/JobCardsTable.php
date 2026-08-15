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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


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
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('customer_display_name')
                    ->label('Customer')
                    ->state(fn ($record) => $record->customer?->display_name ?? '—')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('customer', function (Builder $q) use ($search) {
                            $q->where('full_name', 'like', "%{$search}%")
                                ->orWhere('company_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->join('customers', 'vehicles.customer_id', '=', 'customers.id')
                            ->orderByRaw("COALESCE(customers.company_name, customers.full_name) {$direction}")
                            ->select('vehicles.*');
                    }),

                TextColumn::make('vehicle.plate_number')
                    ->label('Plate')
                    ->searchable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('vehicle.make')
                    ->label('Vehicle')
                    ->formatStateUsing(fn ($record) => $record->vehicle?->make . ' ' . $record->vehicle?->model)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('mechanic.name')
                    ->label('Mechanic')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'       => 'gray',
                        'in_progress'   => 'info',
                        'quality_check' => 'warning',
                        'completed'     => 'success',
                        'cancelled'     => 'danger',
                        default         => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'normal' => 'gray',
                        'urgent' => 'warning',
                        'vip'    => 'danger',
                        default  => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('mileage_at_checkin')
                    ->label('Mileage')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('checked_in_at')
                    ->label('Checked In')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

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

                Filter::make('ready_for_pickup')
                    ->label('Ready for pickup')
                    ->query(fn (Builder $query) => $query->where('status', 'completed')->whereNull('delivered_at')),

                Filter::make('priority_active')
                    ->label('VIP/Urgent (active)')
                    ->query(fn (Builder $query) => $query
                        ->whereIn('priority', ['vip', 'urgent'])
                        ->whereNotIn('status', ['completed', 'cancelled'])
                    ),

                Filter::make('completed_today')
                    ->label('Completed today')
                    ->query(fn (Builder $query) => $query->where('status', 'completed')->whereDate('completed_at', today())),

                TrashedFilter::make(),

            ])
            // app/Filament/Resources/JobCards/Tables/JobCardsTable.php
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user->can('job_cards.view_all')) {
                    return $query;
                }
                if ($user->can('job_cards.view_own')) {
                    return $query->where('mechanic_id', $user->id);
                }
                return $query->whereRaw('1 = 0');
            })
            ->deferFilters(false)
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
