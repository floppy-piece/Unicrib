<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'category',
        'phone',
        'is_verified',
        'paystack_subaccount_code',
        'telegram_chat_id',
        'access_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',          // <--- Fixes the PostgreSQL boolean vs integer error
        'access_expires_at' => 'datetime',
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
            'is_verified' => 'boolean',
            'access_expires_at' => 'datetime',
        ];
    }
    public function scopeStudents($query)
    {
        return $query->where('category', 'student');
    }

    public function scopeLandlords($query)
    {
        return $query->where('category', 'landlord');
    }

    public function scopeadmins($query)
    {
        return $query->where('category', 'admin');
    }

    public function bookingsAsStudent()
{
    return $this->hasMany(Booking::class, 'student_id');
}

public function bookingsAsLandlord()
{
    return $this->hasMany(Booking::class, 'landlord_id');
}

    // Helpers
    
    public function isstudent()
    {
        return $this->category === 'student';
    }

    public function islandlord()
    {
        return $this->category === 'landlord';
    }

    public function isadmin()
    {
        return $this->category === 'admin';
    }

    public function hasValidStudentAccess(): bool
    {
        if ($this->category !== 'student') {
            return false;
        }
        if (!$this->is_verified) {
            return false;
        }
        if (!$this->access_expires_at) {
            return false;
        }
        return now()->lt($this->access_expires_at);
    }

    public function hasValidLandlordAccess(): bool
    {
        return $this->category === 'landlord' && $this->is_verified;
    }

    //properties
    public function properties()
    {
        return $this->hasMany(Property::class, 'landlord_id');
    }

}
