<?php

namespace App\Filament\Resources\JobCards\RelationManagers;

use App\Models\InventoryItem;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartsUsedRelationManager extends RelationManager
{
    protected static string $relationship = 'partsUsed';

    protected static ?string $recordTitleAttribute = 'inventoryItem.name';

    protected static ?string $title = 'Parts Used';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Add Part')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('inventory_item_id')
                            ->label('Part')
                            ->relationship('inventoryItem', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) =>
                            "{$record->part_number} - {$record->name} (Stock: {$record->quantity_on_hand})"
                            )
                            ->searchable(['name', 'part_number'])
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $item = InventoryItem::find($state);
                                    if ($item) {
                                        $set('unit_cost', $item->unit_cost);
                                        $set('unit_price', $item->selling_price);
                                        // ✅ Calculate total immediately
                                        $set('total', $item->selling_price);
                                    }
                                }
                            })
                            ->columnSpan(2),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->step(1)
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $unitPrice = $get('unit_price') ?? 0;
                                $discount = $get('discount') ?? 0;
                                $total = ($state * $unitPrice) - $discount;
                                $set('total', round(max(0, $total), 2));
                            })
                            ->columnSpan(1),

                        TextInput::make('unit_price')
                            ->label('Selling Price (ETB)')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->prefix('ETB')
                            ->step(0.01)
                            ->live()
                            ->disabled(fn () => auth()->user()->hasRole('mechanic'))
                            ->dehydrated()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $quantity = $get('quantity') ?? 1;
                                $discount = $get('discount') ?? 0;
                                $total = ($quantity * $state) - $discount;
                                $set('total', round(max(0, $total), 2));
                            })
                            ->columnSpan(1),

                        TextInput::make('unit_cost')
                            ->label('Unit Cost (ETB)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->prefix('ETB')
                            ->step(0.01)
                            ->disabled(fn () => auth()->user()->hasRole('mechanic'))
                            ->dehydrated()
                            ->columnSpan(1),

                        TextInput::make('discount')
                            ->label('Discount (ETB)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->prefix('ETB')
                            ->step(0.01)
                            ->live()
                            ->disabled(fn () => auth()->user()->hasRole('mechanic'))
                            ->dehydrated()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $quantity = $get('quantity') ?? 1;
                                $unitPrice = $get('unit_price') ?? 0;
                                $total = ($quantity * $unitPrice) - $state;
                                $set('total', round(max(0, $total), 2));
                            })
                            ->columnSpan(1),
                        // ✅ FIXED: Total field with default value and proper hydration
                        TextInput::make('total')
                            ->label('Total (ETB)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->prefix('ETB')
                            ->default(0)
                            ->afterStateHydrated(function ($state, Set $set) {
                                // Ensure total is never null
                                if ($state === null) {
                                    $set('total', 0);
                                }
                            })
                            ->columnSpan(2),
                    ])
                    ->compact()
                    ->collapsible(false),
            ]);
    }

    // ✅ ADD THIS: Mutate form data before create to ensure total is set
    public function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure total is set
        if (!isset($data['total']) || $data['total'] === null || $data['total'] === '') {
            $quantity = $data['quantity'] ?? 1;
            $unitPrice = $data['unit_price'] ?? 0;
            $discount = $data['discount'] ?? 0;
            $data['total'] = round(max(0, ($quantity * $unitPrice) - $discount), 2);
        }

        return $data;
    }

    // ✅ ADD THIS: Mutate form data before update to ensure total is set
    public function mutateFormDataBeforeUpdate(array $data): array
    {
        // Ensure total is set
        if (!isset($data['total']) || $data['total'] === null || $data['total'] === '') {
            $quantity = $data['quantity'] ?? 1;
            $unitPrice = $data['unit_price'] ?? 0;
            $discount = $data['discount'] ?? 0;
            $data['total'] = round(max(0, ($quantity * $unitPrice) - $discount), 2);
        }

        return $data;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventoryItem.part_number')
                    ->label('Part #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('inventoryItem.name')
                    ->label('Part Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money('ETB')
                    ->sortable(),

                TextColumn::make('discount')
                    ->label('Discount')
                    ->money('ETB')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('ETB')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('inventory_item_id')
                    ->label('Part')
                    ->options(fn () => InventoryItem::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                    )
                    ->searchable(),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Add Part'),
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        $query = $this->getRelationship()->getQuery();

        $user = Filament::auth()->user();

        if ($user && ! $user->hasRole(['admin', 'manager'])) {
            if ($user->branch_id) {
                $query->whereHas('inventoryItem', function ($q) use ($user) {
                    $q->where('branch_id', $user->branch_id);
                });
            }
        }

        return $query;
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
