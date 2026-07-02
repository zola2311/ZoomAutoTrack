<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartUsed extends Model
{
    protected $table = 'parts_used';
    protected $guarded = ['id'];
    protected $fillable = [
        'job_card_id',
        'inventory_item_id',
        'quantity',
        'unit_cost',
        'unit_price',
        'discount',
        'total',
    ];
    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function jobCard() { return $this->belongsTo(JobCard::class); }
    public function inventoryItem() { return $this->belongsTo(InventoryItem::class); }
}
