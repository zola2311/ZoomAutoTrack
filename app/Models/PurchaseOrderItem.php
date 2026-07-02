<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'purchase_order_id',
        'inventory_item_id',
        'description',
        'supplier_part_number',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'discount',
        'tax',
        'total',
        'notes',
    ];
    protected $casts = [
        'quantity_ordered' => 'integer',
        'quantity_received' => 'integer',
        'unit_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function inventoryItem() { return $this->belongsTo(InventoryItem::class); }
}
