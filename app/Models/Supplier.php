<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean'];
    protected $fillable = [
        'branch_id',
        'name',
        'contact_person',
        'phone',
        'secondary_phone',
        'email',
        'city',
        'area',
        'address',
        'map_link',
        'tin_number',
        'notes',
        'is_active',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function supplierPrices() { return $this->hasMany(InventorySupplierPrice::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
}
