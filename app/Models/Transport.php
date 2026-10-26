<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    protected $fillable = [
        'booking_id',
        'property_id',
        'student_id',
        'driver_id',
        'student_name',
        'student_phone',
        'requested_date',
        'requested_time',
        'assigned_at',
        'status',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'requested_time' => 'datetime:H:i',
        'assigned_at'    => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
