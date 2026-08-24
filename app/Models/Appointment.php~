<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'customer_id',
        'vehicle_id',
        'requested_date',
        'requested_time_slot',
        'service_types',
        'other_service_description',
        'notes',
        'status',
        'source',
        'job_card_id',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'reviewed_at' => 'datetime',
        'service_types' => 'array',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function jobCard() { return $this->belongsTo(JobCard::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
