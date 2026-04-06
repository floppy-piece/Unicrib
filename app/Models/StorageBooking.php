<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'months',
        'amount',
        'status',      // pending, paid, cancelled, completed
        'reference',   // payment reference / receipt
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
