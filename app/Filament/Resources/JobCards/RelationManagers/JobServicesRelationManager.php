<?php

namespace App\Filament\Resources\JobCards\RelationManagers;

use App\Models\JobService;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;


class JobServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $title = 'Labor Services';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('assigned_mechanic_id')
                            ->label('Assigned Mechanic')
                            ->options(fn () => User::query()
                                ->whereHas('roles', fn ($q) => $q->where('name', 'mechanic'))
                                ->orWhere('role', 'mechanic')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        TextInput::make('description')
                            ->label('Service Description')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Oil Change, Brake Pad Replacement'),

                        TextInput::make('labor_cost')
                            ->label('Labor Cost (ETB)')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->prefix('ETB')
                            ->step(0.01),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->nullable(),

                        Toggle::make('is_completed')
                            ->label('Completed')
                            ->default(false)
                            ->helperText('Mark as completed when the service is done'),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Service')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('assignedMechanic.name')
                    ->label('Mechanic')
                    ->searchable()
                    ->placeholder('Not Assigned')
                    ->toggleable(),

                TextColumn::make('labor_cost')
                    ->label('Cost')
                    ->money('ETB')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),

                IconColumn::make('is_completed')
                    ->label('Done')
                    ->boolean(),

                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('assigned_mechanic_id')
                    ->label('Mechanic')
                    ->options(fn () => User::query()
                        ->whereHas('roles', fn ($q) => $q->where('name', 'mechanic'))
                        ->orWhere('role', 'mechanic')
                        ->orderBy('name')
                        ->pluck('name', 'id')
                    ),
            ])
            // ✅ FIXED: Filament 4.x action namespace
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make(),
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
