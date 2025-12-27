<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'category',
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
        //return $this->category === 'landlord' && $this->is_verified;
        return $this->category === 'landlord';
    }

    //properties
    public function properties()
    {
        return $this->hasMany(Property::class, 'landlord_id');
    }

}
