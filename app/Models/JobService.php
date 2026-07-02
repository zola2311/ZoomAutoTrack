<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobService extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'labor_cost' => 'decimal:2',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];
    protected $fillable = [
        'job_card_id',
        'assigned_mechanic_id',
        'description',
        'labor_cost',
        'status',
        'notes',
        'voice_note_id',
        'is_completed',
        'completed_at',
    ];

    public function jobCard() { return $this->belongsTo(JobCard::class); }
    public function assignedMechanic() { return $this->belongsTo(User::class, 'assigned_mechanic_id'); }
}
