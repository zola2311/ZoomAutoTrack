<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'ordered_at' => 'datetime',
        'received_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
    protected $fillable = [
        'branch_id',
        'supplier_id',
        'requested_by',
        'approved_by',
        'po_number',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'requested_at',
        'approved_at',
        'ordered_at',
        'received_at',
        'cancelled_at',
        'notes',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function requestedBy() { return $this->belongsTo(User::class, 'requested_by'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
    public function stockMovements() { return $this->morphMany(StockMovement::class, 'reference'); }
}
