<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use App\Filament\Resources\JobCards\JobCardResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobCardsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobCards';

    protected static ?string $recordTitleAttribute = 'job_number';

    protected static ?string $title = 'Service History';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_number')
                    ->label('Job #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('mileage_at_checkin')
                    ->label('Mileage')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable(),

                TextColumn::make('customer_complaint')
                    ->label('Complaint')
                    ->limit(40)
                    ->placeholder('—'),

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

                TextColumn::make('checked_in_at')
                    ->label('Checked In')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->defaultSort('checked_in_at', 'desc')
            ->recordUrl(
                fn ($record) => JobCardResource::getUrl('view', ['record' => $record])
            )
            ->recordActions([])
            ->toolbarActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
