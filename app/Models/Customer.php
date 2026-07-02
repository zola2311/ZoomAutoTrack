<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'branch_id',
        'customer_code',
        'type',
        'full_name',
        'company_name',
        'phone',
        'secondary_phone',
        'email',
        'tin_number',
        'address',
        'preferred_language',
        'loyalty_points',
        'notes',
    ];
    protected $guarded = ['id'];
    protected $casts = ['loyalty_points' => 'integer'];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function vehicles() { return $this->hasMany(Vehicle::class); }
    public function jobCards() { return $this->hasMany(JobCard::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
}
