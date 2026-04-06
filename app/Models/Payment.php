<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'amount',
        'currency',
        'status',
        'provider_payload',
    ];

    protected $casts = [
        'provider_payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
