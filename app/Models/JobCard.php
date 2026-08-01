<?php

namespace App\Models;

use App\Concerns\HandlesMediaUploads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class JobCard extends Model
{
    use SoftDeletes ,HandlesMediaUploads;

    protected $guarded = ['id'];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'estimated_completion_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];
    protected $fillable = [
        'branch_id',
        'customer_id',
        'vehicle_id',
        'service_advisor_id',
        'mechanic_id',
        'job_number',
        'mileage_at_checkin',
        'fuel_level',
        'customer_complaint',
        'customer_complaint_voice_id',
        'mechanic_notes',
        'mechanic_notes_voice_id',
        'status',
        'priority',
        'checked_in_at',
        'estimated_completion_at',
        'completed_at',
        'delivered_at',
        'customer_complaint_voice',
        'mechanic_notes_voice',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function serviceAdvisor() { return $this->belongsTo(User::class, 'service_advisor_id'); }
    public function mechanic() { return $this->belongsTo(User::class, 'mechanic_id'); }
    public function services() { return $this->hasMany(JobService::class); }
    public function partsUsed() { return $this->hasMany(PartUsed::class); }
    public function inspections() { return $this->hasMany(VehicleInspection::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
    public function media() { return $this->morphMany(Media::class, 'model'); }
    protected static function booted(): void
    {
        static::creating(function (JobCard $jobCard) {
            $jobCard->job_number = 'JOB-' . str_pad(
                    JobCard::withTrashed()->count() + 1,
                    5, '0', STR_PAD_LEFT
                );
            $jobCard->checked_in_at = now(); // ← add this line
        });
    }
    public function hasIncompleteServices(): bool
    {
        return $this->services()
            ->where('is_completed', false)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }

    public function photosIn(string $collection)
    {
        return $this->media()->where('collection', $collection)->orderBy('created_at');
    }

    public function checkinPhotos()   { return $this->photosIn('checkin_photos'); }
    public function damagePhotos()    { return $this->photosIn('damage_photos'); }
    public function beforePhotos()    { return $this->photosIn('before_photos'); }
    public function afterPhotos()     { return $this->photosIn('after_photos'); }
    public function checkoutPhotos()  { return $this->photosIn('checkout_photos'); }



}
