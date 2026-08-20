<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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

    public function nextServiceDue(): array
    {
        $lastService = $this->jobCards()
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->first();

        $baseMileage = $lastService?->mileage_at_checkin ?? $this->current_mileage;
        $baseDate = $lastService?->completed_at ?? $this->created_at;

        $dueMileage = $baseMileage + $this->service_interval_km;
        $dueDate = $baseDate->copy()->addMonths($this->service_interval_months);

        $kmRemaining = max(0, $dueMileage - $this->current_mileage);
        $daysRemaining = now()->diffInDays($dueDate, false);

        $isDue = $kmRemaining <= 0 || $daysRemaining <= 0;

        return [
            'due_mileage'     => $dueMileage,
            'due_date'        => $dueDate,
            'km_remaining'    => $kmRemaining,
            'days_remaining'  => $daysRemaining,
            'is_due'          => $isDue,
            'due_reason'      => $kmRemaining <= 0 ? 'mileage' : ($daysRemaining <= 0 ? 'time' : null),
        ];
    }

    /**
     * Most recent inspection with scoreable answers, used for the
     * diagnostic report card on the passport and customer dashboard.
     */
    public function latestInspection(): ?VehicleInspection
    {
        return $this->inspections()
            ->with('answers.inspectionItem')
            ->whereHas('answers')
            ->orderByDesc('inspected_at')
            ->first();
    }

    /**
     * Convenience wrapper — returns [] if there's no scoreable inspection yet.
     */
    public function latestHealthScores(): array
    {
        return $this->latestInspection()?->healthScores() ?? [];
    }

    /**
     * The full public passport URL for this vehicle (used for the QR
     * image and for printing/sharing).
     */
    public function passportUrl(): ?string
    {
        return $this->qr_code ? route('passport.show', $this->qr_code) : null;
    }

    protected static function booted(): void
    {
        static::creating(function (Vehicle $vehicle) {
            if (empty($vehicle->qr_code)) {
                $vehicle->qr_code = (string) Str::uuid();
            }
        });
    }
}
