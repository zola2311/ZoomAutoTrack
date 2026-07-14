<?php

namespace App\Filament\Resources\InventoryItems\Pages;

use App\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Models\StockMovement;
use App\Models\Supplier;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewInventoryItem extends ViewRecord
{
    protected static string $resource = InventoryItemResource::class;

    // Movement types that ADD to quantity_on_hand
    protected array $incomingTypes = ['purchase', 'return', 'adjustment_add', 'initial'];

    // Movement types that SUBTRACT from quantity_on_hand
    protected array $outgoingTypes = ['usage', 'adjustment_remove', 'damaged', 'expired'];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('adjustStock')
                ->label('Adjust Stock')
                ->icon('heroicon-o-arrows-up-down')
                ->color('warning')
                ->schema([
                    Select::make('type')
                        ->label('Movement Type')
                        ->options([
                            'purchase'         => 'Purchase (Stock In)',
                            'return'           => 'Return (Stock In)',
                            'adjustment_add'   => 'Adjustment (+)',
                            'usage'            => 'Usage (Stock Out)',
                            'adjustment_remove'=> 'Adjustment (-)',
                            'damaged'          => 'Damaged / Written Off',
                            'expired'          => 'Expired',
                        ])
                        ->required()
                        ->live(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->required()
                        ->minValue(1),

                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->options(fn () => Supplier::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->visible(fn ($get) => $get('type') === 'purchase'),

                    TextInput::make('unit_cost')
                        ->label('Unit Cost')
                        ->numeric()
                        ->prefix('ETB')
                        ->visible(fn ($get) => in_array($get('type'), ['purchase', 'return'])),

                    Textarea::make('notes')
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    $item = $this->record;
                    $isIncoming = in_array($data['type'], $this->incomingTypes);
                    $delta = $isIncoming ? $data['quantity'] : -$data['quantity'];

                    if (! $isIncoming && $item->quantity_on_hand + $delta < 0) {
                        Notification::make()
                            ->title('Not enough stock on hand for this movement')
                            ->danger()
                            ->send();
                        return;
                    }

                    DB::transaction(function () use ($item, $data, $delta) {
                        StockMovement::create([
                            'branch_id'         => $item->branch_id,
                            'inventory_item_id' => $item->id,
                            'supplier_id'       => $data['supplier_id'] ?? null,
                            'type'              => $data['type'],
                            'quantity'          => $data['quantity'],
                            'unit_cost'         => $data['unit_cost'] ?? null,
                            'notes'             => $data['notes'] ?? null,
                            'created_by'        => auth()->id(),
                        ]);

                        $item->update([
                            'quantity_on_hand' => $item->quantity_on_hand + $delta,
                        ]);
                    });

                    Notification::make()
                        ->title('Stock updated')
                        ->success()
                        ->send();

                    $this->record->refresh();
                }),

            EditAction::make(),
        ];
    }
}
