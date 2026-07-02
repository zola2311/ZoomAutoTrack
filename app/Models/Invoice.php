<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $fillable = [
        'branch_id',
        'job_card_id',
        'customer_id',
        'invoice_number',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'balance',
        'status',
        'issued_at',
        'due_at',
    ];
    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function jobCard() { return $this->belongsTo(JobCard::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
