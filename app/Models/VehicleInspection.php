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

    protected static function booted(): void
    {
        static::creating(function (VehicleInspection $inspection) {
            if (empty($inspection->vehicle_id) && $inspection->job_card_id) {
                $jobCard = JobCard::find($inspection->job_card_id);
                if ($jobCard) {
                    $inspection->vehicle_id = $jobCard->vehicle_id;
                }
            }
        });
    }

    /**
     * Per-system scores for the diagnostic report card, grouped by the
     * inspection item's `category` (e.g. "Engine", "Brakes", "AC system").
     * Returns e.g. ['Engine' => 92, 'Brakes' => 61, ...] plus an
     * 'Overall' key averaging every scored answer.
     *
     * Answers with no score (n/a, or an item with no category) are
     * excluded rather than dragging the average down.
     */
    public function healthScores(): array
    {
        $answers = $this->answers()->with('inspectionItem')->get()
            ->filter(fn (VehicleInspectionAnswer $a) => $a->score !== null && $a->inspectionItem?->category);

        if ($answers->isEmpty()) {
            return [];
        }

        $byCategory = $answers
            ->groupBy(fn (VehicleInspectionAnswer $a) => $a->inspectionItem->category)
            ->map(fn ($group) => (int) round($group->avg('score')))
            ->sortBy(fn ($score, $category) => $category)
            ->all();

        $byCategory['Overall'] = (int) round($answers->avg('score'));

        return $byCategory;
    }
}
