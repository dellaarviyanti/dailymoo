<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'session',
        'feed_type',
        'offered_amount',
        'consumed_amount',
        'cow_weight',
        'recorded_at',
    ];

    protected $casts = [
        'offered_amount' => 'float',
        'consumed_amount' => 'float',
        'cow_weight' => 'float',
        'recorded_at' => 'datetime',
    ];
}

