<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionItem extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'category',
        'input_type',
        'requires_photo',
        'requires_voice_note',
        'sort_order',
        'is_active',
    ];
    protected $casts = [
        'requires_photo' => 'boolean',
        'requires_voice_note' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function answers() { return $this->hasMany(VehicleInspectionAnswer::class); }
}
