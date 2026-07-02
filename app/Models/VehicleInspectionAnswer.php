<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInspectionAnswer extends Model
{
    protected $guarded = ['id'];

    public function vehicleInspection() { return $this->belongsTo(VehicleInspection::class); }
    public function inspectionItem() { return $this->belongsTo(InspectionItem::class); }
}
