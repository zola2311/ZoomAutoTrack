<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'booking_key', 'overview',
        'points', 'image', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'points' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            if (blank($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
