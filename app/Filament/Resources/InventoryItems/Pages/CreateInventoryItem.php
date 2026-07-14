<?php

namespace App\Filament\Resources\InventoryItems\Pages;

use App\Filament\Resources\InventoryItems\InventoryItemResource;

use App\Models\StockMovement;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryItem extends CreateRecord
{
    protected static string $resource = InventoryItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['branch_id'])) {
            $data['branch_id'] = auth()->user()->branch_id ?? 1;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Log the opening stock as an initial movement, if any quantity was declared
        if ($this->record->quantity_on_hand > 0) {
            StockMovement::create([
                'branch_id'         => $this->record->branch_id,
                'inventory_item_id' => $this->record->id,
                'type'              => 'initial',
                'quantity'          => $this->record->quantity_on_hand,
                'unit_cost'         => $this->record->unit_cost,
                'notes'             => 'Opening stock at item creation',
                'created_by'        => auth()->id(),
            ]);
        }
    }
}
