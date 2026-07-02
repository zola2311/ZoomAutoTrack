<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySupplierPrice extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'last_price' => 'decimal:2',
        'available_quantity' => 'integer',
        'quoted_at' => 'datetime',
        'last_checked_at' => 'datetime',
    ];
    protected $fillable = [
        'inventory_item_id',
        'supplier_id',
        'supplier_part_number',
        'last_price',
        'currency',
        'available_quantity',
        'condition',
        'quality_grade',
        'quoted_at',
        'last_checked_at',
        'checked_by',
        'notes',
    ];

    public function inventoryItem() { return $this->belongsTo(InventoryItem::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function checkedBy() { return $this->belongsTo(User::class, 'checked_by'); }
}
