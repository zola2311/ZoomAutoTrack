<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInspection extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['inspected_at' => 'datetime'];
    protected $fillable = [
        'job_card_id',
        'vehicle_id',
        'inspected_by',
        'type',
        'notes',
        'voice_note_id',
        'inspected_at',
    ];
    public function jobCard() { return $this->belongsTo(JobCard::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function inspector() { return $this->belongsTo(User::class, 'inspected_by'); }
    public function answers() { return $this->hasMany(VehicleInspectionAnswer::class); }
}
