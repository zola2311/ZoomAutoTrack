<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity_on_hand' => 'integer',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
    ];
    protected $fillable = [
        'branch_id',
        'name',
        'part_number',
        'category',
        'brand',
        'unit_cost',
        'selling_price',
        'quantity_on_hand',
        'minimum_stock',
        'location',
        'is_active',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function supplierPrices() { return $this->hasMany(InventorySupplierPrice::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function partsUsed() { return $this->hasMany(PartUsed::class); }
    public function purchaseOrderItems() { return $this->hasMany(PurchaseOrderItem::class); }
}
