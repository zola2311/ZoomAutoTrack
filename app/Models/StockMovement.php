<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];
    protected $fillable = [
        'branch_id',
        'inventory_item_id',
        'supplier_id',
        'type',
        'quantity',
        'unit_cost',
        'unit_price',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function inventoryItem() { return $this->belongsTo(InventoryItem::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function reference() { return $this->morphTo(); }
}
