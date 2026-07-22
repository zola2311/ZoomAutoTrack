<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'invoice_id',
        'amount',
        'method',
        'reference_number',
        'notes',
        'received_by',
        'paid_at',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }

}

