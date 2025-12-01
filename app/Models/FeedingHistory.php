<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingHistory extends Model
{
    use HasFactory;

    protected $table = 'feeding_history';

    protected $fillable = [
        'cow_id',
        'cow_weight',
        'feed_given',
        'feed_eaten',
        'date',
    ];

    protected $casts = [
        'cow_id' => 'integer',
        'cow_weight' => 'float',
        'feed_given' => 'float',
        'feed_eaten' => 'float',
        'date' => 'date',
    ];
}
