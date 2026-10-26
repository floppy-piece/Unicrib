<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'vehicle_model',
        'vehicle_plate',
        'status',
    ];

    public function transports()
    {
        return $this->hasMany(Transport::class);
    }
}