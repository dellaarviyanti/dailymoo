<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CowWeight extends Model
{
    use HasFactory;

    protected $table = 'cow_weights';

    protected $fillable = [
        'cow_id',
        'weight',
        'measured_at',
        'notes',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'measured_at' => 'date',
    ];

    /**
     * Get latest weight for each cow
     */
    public static function getLatestWeights()
    {
        return static::select('cow_id', 'weight', 'measured_at')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('cow_weights')
                    ->groupBy('cow_id');
            })
            ->get()
            ->keyBy('cow_id');
    }

    /**
     * Get weight history for a specific cow
     */
    public static function getWeightHistory(int $cowId, int $limit = 12)
    {
        return static::where('cow_id', $cowId)
            ->orderBy('measured_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all weights for chart (grouped by date)
     */
    public static function getWeightsForChart()
    {
        return static::selectRaw('
                measured_at,
                AVG(weight) as avg_weight,
                MIN(weight) as min_weight,
                MAX(weight) as max_weight,
                COUNT(*) as cow_count
            ')
            ->groupBy('measured_at')
            ->orderBy('measured_at', 'asc')
            ->get();
    }
}

