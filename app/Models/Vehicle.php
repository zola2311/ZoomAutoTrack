<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'branch_id',
        'customer_id',
        'plate_number',
        'make',
        'model',
        'year',
        'color',
        'chassis_number',
        'engine_number',
        'current_mileage',
        'fuel_type',
        'transmission',
        'qr_code',
    ];
    protected $guarded = ['id'];
    protected $casts = ['year' => 'integer', 'current_mileage' => 'integer'];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function jobCards() { return $this->hasMany(JobCard::class); }
    public function inspections() { return $this->hasMany(VehicleInspection::class); }
    public function media() { return $this->morphMany(Media::class, 'model'); }
}
