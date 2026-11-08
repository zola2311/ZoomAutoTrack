<?php

namespace App\Observers;

use App\Models\PartUsed;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class PartsUsedObserver
{
    /**
     * Deduct inventory and record stock movement when a part is assigned to a job card.
     */
    public function created(PartUsed $partsUsed): void
    {
        $item = InventoryItem::find($partsUsed->inventory_item_id);
        if (! $item) {
            return;
        }

        $branchId = $partsUsed->jobCard?->branch_id ?? $item->branch_id ?? 1;

        DB::transaction(function () use ($item, $partsUsed, $branchId) {
            $item->decrement('quantity_on_hand', (int) $partsUsed->quantity);

            StockMovement::create([
                'branch_id'         => $branchId,
                'inventory_item_id' => $item->id,
                'supplier_id'       => null,
                'type'              => 'usage',
                'quantity'          => (int) $partsUsed->quantity,
                'unit_cost'         => $partsUsed->unit_cost ?? $item->unit_cost,
                'unit_price'        => $partsUsed->unit_price ?? $item->selling_price,
                'reference_type'    => 'job_card',
                'reference_id'      => $partsUsed->job_card_id,
                'notes'             => 'Used on Job Card ' . ($partsUsed->jobCard?->job_number ?? '#' . $partsUsed->job_card_id),
                'created_by'        => auth()->id(),
            ]);
        });
    }

    /**
     * Adjust inventory if the quantity or part item was updated.
     */
    public function updated(PartUsed $partsUsed): void
    {
        if ($partsUsed->wasChanged('inventory_item_id')) {
            $oldItemId = $partsUsed->getOriginal('inventory_item_id');
            $oldQuantity = (int) $partsUsed->getOriginal('quantity');
            $oldItem = InventoryItem::find($oldItemId);
            if ($oldItem) {
                $oldItem->increment('quantity_on_hand', $oldQuantity);
            }

            $newItem = InventoryItem::find($partsUsed->inventory_item_id);
            if ($newItem) {
                $newItem->decrement('quantity_on_hand', (int) $partsUsed->quantity);
            }
        } elseif ($partsUsed->wasChanged('quantity')) {
            $delta = (int) $partsUsed->quantity - (int) $partsUsed->getOriginal('quantity');
            $item = InventoryItem::find($partsUsed->inventory_item_id);
            if ($item && $delta !== 0) {
                if ($delta > 0) {
                    $item->decrement('quantity_on_hand', $delta);
                } else {
                    $item->increment('quantity_on_hand', abs($delta));
                }

                StockMovement::create([
                    'branch_id'         => $partsUsed->jobCard?->branch_id ?? $item->branch_id ?? 1,
                    'inventory_item_id' => $item->id,
                    'type'              => $delta > 0 ? 'usage' : 'adjustment_add',
                    'quantity'          => abs($delta),
                    'unit_cost'         => $partsUsed->unit_cost ?? $item->unit_cost,
                    'unit_price'        => $partsUsed->unit_price ?? $item->selling_price,
                    'reference_type'    => 'job_card',
                    'reference_id'      => $partsUsed->job_card_id,
                    'notes'             => 'Quantity updated on Job Card ' . ($partsUsed->jobCard?->job_number ?? '#' . $partsUsed->job_card_id),
                    'created_by'        => auth()->id(),
                ]);
            }
        }
    }

    /**
     * Restore stock and record movement when a part is removed from a job card.
     */
    public function deleted(PartUsed $partsUsed): void
    {
        $item = InventoryItem::find($partsUsed->inventory_item_id);
        if ($item) {
            $branchId = $partsUsed->jobCard?->branch_id ?? $item->branch_id ?? 1;

            DB::transaction(function () use ($item, $partsUsed, $branchId) {
                $item->increment('quantity_on_hand', (int) $partsUsed->quantity);

                StockMovement::create([
                    'branch_id'         => $branchId,
                    'inventory_item_id' => $item->id,
                    'supplier_id'       => null,
                    'type'              => 'return',
                    'quantity'          => (int) $partsUsed->quantity,
                    'unit_cost'         => $partsUsed->unit_cost ?? $item->unit_cost,
                    'unit_price'        => $partsUsed->unit_price ?? $item->selling_price,
                    'reference_type'    => 'job_card',
                    'reference_id'      => $partsUsed->job_card_id,
                    'notes'             => 'Removed from Job Card ' . ($partsUsed->jobCard?->job_number ?? '#' . $partsUsed->job_card_id),
                    'created_by'        => auth()->id(),
                ]);
            });
        }

        $this->saved($partsUsed);
    }

    public function saved($model): void
    {
        $jobCard = $model->jobCard;
        if (! $jobCard) {
            return;
        }

        // Reopen job if it was completed and new work was added
        if ($jobCard->status === 'completed') {
            $jobCard->update(['status' => 'in_progress', 'completed_at' => null]);
        }

        // Resync invoice if one exists
        $invoice = \App\Models\Invoice::where('job_card_id', $jobCard->id)->first();
        if ($invoice) {
            $invoice->resyncFromJobCard();
        }
    }
}
