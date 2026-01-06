<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'checkout_request_id',
        'merchant_request_id',
        'mpesa_receipt',
        'amount',
        'phone',
        'status',
        'purpose',
        'raw_callback',
    ];

    protected $casts = [
        'raw_callback' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
