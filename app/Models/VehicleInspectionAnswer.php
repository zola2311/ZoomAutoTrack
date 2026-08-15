<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInspectionAnswer extends Model
{
    protected $guarded = ['id'];

    /**
     * Maps the existing `result` values (pass/fail/warning/n/a) to a 0-100
     * score so we can build the diagnostic report card without adding a
     * new column. Adjust the weights here if you want a different curve.
     */
    public const RESULT_SCORES = [
        'pass' => 100,
        'warning' => 60,
        'fail' => 20,
        // 'n/a' is intentionally omitted — excluded from scoring, see scope() below.
    ];

    public function vehicleInspection() { return $this->belongsTo(VehicleInspection::class); }
    public function inspectionItem() { return $this->belongsTo(InspectionItem::class); }

    /**
     * Numeric score for this single answer, or null if it doesn't count
     * toward scoring (e.g. "n/a" or an unrecognized result).
     */
    public function getScoreAttribute(): ?int
    {
        return self::RESULT_SCORES[$this->result] ?? null;
    }
}
