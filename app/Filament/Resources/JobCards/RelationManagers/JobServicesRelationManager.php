<?php

namespace App\Filament\Resources\JobCards\RelationManagers;

use App\Models\JobService;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section; // ✅ Kept Filament v4 import
use Filament\Schemas\Schema;              // ✅ Kept Filament v4 import
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
            ->components([
                Section::make('Add Labor Service')
                    ->columnSpanFull() // 🌟 FIX: Forces the section to take up the entire modal width
                    ->columns(2)       // Keeps your internal fields nicely side-by-side
                    ->schema([
                        // Row 1: Assigned Mechanic + Status
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
                            ->nullable()
                            ->placeholder('Select a mechanic')
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required()
                            ->columnSpan(1),

                        // Row 2: Service Description - FULL WIDTH
                        TextInput::make('description')
                            ->label('Service Description')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Oil Change, Brake Pad Replacement')
                            ->columnSpan(2),

                        // Row 3: Labor Cost + Toggle side by side
                        TextInput::make('labor_cost')
                            ->label('Labor Cost (ETB)')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->prefix('ETB')
                            ->step(0.01)
                            ->columnSpan(1),

                        Toggle::make('is_completed')
                            ->label('Mark as Completed')
                            ->default(false)
                            ->helperText('Check when this service is done')
                            ->columnSpan(1),

                        // Row 4: Notes - FULL WIDTH
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->nullable()
                            ->placeholder('Additional notes about this service...')
                            ->columnSpan(2),
                    ])
                    ->compact()
                    ->collapsible(false),
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
                    ->weight('medium')
                    ->grow()
                    ->wrap(),

                TextColumn::make('assignedMechanic.name')
                    ->label('Mechanic')
                    ->searchable()
                    ->placeholder('Not Assigned')
                    ->toggleable()
                    ->wrap(),

                TextColumn::make('labor_cost')
                    ->label('Cost')
                    ->money('ETB')
                    ->sortable()
                    ->alignRight(),

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
                    ->boolean()
                    ->alignCenter(),

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
