<?php

namespace App\Models;

use App\Concerns\HandlesMediaUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes, HandlesMediaUploads;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            if (empty($quotation->quotation_number)) {
                $latest = static::withTrashed()->max('id') ?? 0;
                $quotation->quotation_number = 'QUO-' . str_pad($latest + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'symptoms'               => 'array',
            'labor_items'            => 'array',
            'parts_items'            => 'array',
            'estimated_labor_total'  => 'decimal:2',
            'estimated_parts_total'  => 'decimal:2',
            'estimated_tax'          => 'decimal:2',
            'estimated_discount'     => 'decimal:2',
            'estimated_total'        => 'decimal:2',
            'valid_until'            => 'date',
            'preferred_date'         => 'date',
            'quoted_at'              => 'datetime',
            'response_email_sent_at' => 'datetime',
        ];
    }

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function quotedBy()
    {
        return $this->belongsTo(User::class, 'quoted_by');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    // Helpers
    public function defectPhotos()
    {
        return $this->media()->where('collection', 'defect_photos');
    }

    public function voiceNotes()
    {
        return $this->media()->where('collection', 'voice_notes');
    }

    public function getVoiceNoteUrlAttribute(): ?string
    {
        if ($this->voice_note_path) {
            return asset($this->voice_note_path);
        }
        $vnMedia = $this->voiceNotes()->first();
        if ($vnMedia) {
            return asset('uploads/' . $vnMedia->path);
        }
        return null;
    }

    public function hasVoiceNote(): bool
    {
        return !empty($this->voice_note_path) || $this->voiceNotes()->exists();
    }

    public function getVehicleDisplayNameAttribute(): string
    {
        $parts = array_filter([$this->year, $this->make, $this->model]);
        $name = implode(' ', $parts);
        if ($this->plate_number) {
            $name .= ' (' . $this->plate_number . ')';
        }
        return $name ?: 'Unspecified Vehicle';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'          => 'Pending Review',
            'in_review'        => 'In Assessment',
            'quoted'           => 'Quotation Sent',
            'accepted'         => 'Accepted by Client',
            'declined'         => 'Declined',
            'converted_to_job' => 'Converted to Work Order',
            default            => ucfirst($this->status),
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending'          => 'warning',
            'in_review'        => 'info',
            'quoted'           => 'success',
            'accepted'         => 'success',
            'declined'         => 'danger',
            'converted_to_job' => 'purple',
            default            => 'gray',
        };
    }
}
