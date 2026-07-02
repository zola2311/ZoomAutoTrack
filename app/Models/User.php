<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function advisedJobCards() { return $this->hasMany(JobCard::class, 'service_advisor_id'); }
    public function mechanicJobCards() { return $this->hasMany(JobCard::class, 'mechanic_id'); }
    public function assignedServices() { return $this->hasMany(JobService::class, 'assigned_mechanic_id'); }
    public function paymentsReceived() { return $this->hasMany(Payment::class, 'received_by'); }
    public function uploadedMedia() { return $this->hasMany(Media::class, 'uploaded_by'); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class); }
}
