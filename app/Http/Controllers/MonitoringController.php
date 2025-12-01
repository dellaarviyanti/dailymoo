<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TemperatureReading;
use App\Models\HumidityReading;
use App\Models\FeedingLog;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\CowWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        /** =============== FILTER SENSOR DATA ================= */
        $filterTemp = $request->get('filter_temp', 'day');
        $filterHumidity = $request->get('filter_humidity', 'day');

        $temperatureSeries = $this->getTelemetrySeries('temperature', $filterTemp);
        $humiditySeries = $this->getTelemetrySeries('humidity', $filterHumidity);

        $sensorData = $this->mergeTelemetrySeries($temperatureSeries, $humiditySeries);

        /** =============== REALTIME LATEST SENSOR ================= */
        $latestTemperature = $temperatureSeries->last();
        $latestHumidity = $humiditySeries->last();

        // Generate ordered sensor data sequence (4 normal, 1 warning, repeat)
        $temperatureSequence = $this->generateOrderedSensorSequence('temperature');
        $humiditySequence = $this->generateOrderedSensorSequence('humidity');
        
        // Get first item from sequence for initial display
        $currentTemperature = $temperatureSequence->first();
        $currentHumidity = $humiditySequence->first();

        /** =============== PEMBELI ================= */
        if ($user->role === 'pembeli') {
            $transactions = Transaction::where('user_id', $user->id)
                ->with('items.product')
                ->latest()
                ->paginate(10);

            return view('monitoring.pembeli', compact('transactions'));
        }

        /** =============== SUPERADMIN + PEGAWAI ================= */
        if (in_array($user->role, ['superadmin', 'pegawai'])) {

            // Users hanya untuk superadmin
            $users = $user->role === 'superadmin' ? User::all() : collect();
            $products = Product::all();

            // Grafik Penjualan Bulanan
            $salesData = Transaction::select(
                DB::raw('DATE_FORMAT(created_at, "%b") as bulan'),
                DB::raw('SUM(total_amount) as pendapatan')
            )
            ->where('created_at', '>=', now()->subMonths(4))
            ->groupBy('bulan')
            ->get();

            $totalRevenue = Transaction::whereMonth('created_at', now()->month)->sum('total_amount');
            $totalTransactions = Transaction::whereMonth('created_at', now()->month)->count();
            $totalProductsSold = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->whereMonth('transactions.created_at', now()->month)
                ->sum('transaction_items.quantity');

            // Weight data for chart: ambil data maksimum 3 bulan terakhir (fallback ke seluruh data jika kosong)
            $weightHistoryQuery = CowWeight::selectRaw('
                    DATE(measured_at) as measured_at,
                    AVG(weight) as avg_weight,
                    MIN(weight) as min_weight,
                    MAX(weight) as max_weight,
                    COUNT(*) as cow_count
                ')
                ->groupBy(DB::raw('DATE(measured_at)'))
                ->orderBy(DB::raw('DATE(measured_at)'), 'asc');

            $weightHistory = (clone $weightHistoryQuery)
                ->where('measured_at', '>=', now()->copy()->subMonths(3)->startOfDay())
                ->get();

            if ($weightHistory->isEmpty()) {
                $weightHistory = $weightHistoryQuery->get();
            }

            $weightHistory = $weightHistory->map(function ($item) {
                $item->measured_at = \Carbon\Carbon::parse($item->measured_at);
                $item->avg_weight = (float) $item->avg_weight;
                $item->min_weight = (float) $item->min_weight;
                $item->max_weight = (float) $item->max_weight;
                return $item;
            });

            $maxWeightPoints = 6;
            if ($weightHistory->count() > $maxWeightPoints) {
                $weightHistory = $weightHistory->slice(-$maxWeightPoints)->values();
            }

            $timelineDates = $weightHistory->pluck('measured_at')->map->format('Y-m-d');

            $historicalData = $this->getFeedingHistory($timelineDates);

            $feedingLookup = $historicalData->keyBy(function ($item) {
                return $item->recorded_at->format('Y-m-d');
            });

            // Ambil data pakan dari notes (ekstrak dari format "Saran pakan: X kg" atau "Bobot baru: Y kg")
            $feedDataByDate = CowWeight::whereIn(DB::raw('DATE(measured_at)'), $timelineDates->toArray())
                ->whereNotNull('notes')
                ->get()
                ->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->measured_at)->format('Y-m-d');
                })
                ->map(function ($items) {
                    $feedValues = $items->map(function ($item) {
                        // Ekstrak nilai pakan dari notes
                        $notes = $item->notes ?? '';
                        
                        // Cek apakah ada "Bobot baru" (jika tidak setuju)
                        if (preg_match('/Bobot baru:\s*([\d.]+)\s*kg/', $notes, $matches)) {
                            return (float) $matches[1];
                        }
                        // Jika tidak, ambil dari "Saran pakan" (jika setuju)
                        elseif (preg_match('/Saran pakan:\s*([\d.]+)\s*kg/', $notes, $matches)) {
                            // Cek apakah setuju
                            if (strpos($notes, 'Persetujuan: Setuju') !== false) {
                                return (float) $matches[1];
                            }
                        }
                        
                        return null;
                    })
                    ->filter(function ($val) {
                        return $val !== null && $val > 0;
                    })
                    ->values();
                    
                    if ($feedValues->isEmpty()) {
                        return null;
                    }
                    
                    return (object) [
                        'avg' => $feedValues->avg(),
                        'min' => $feedValues->min(),
                        'max' => $feedValues->max(),
                    ];
                });

            $timelineSeries = $weightHistory->map(function ($entry) use ($feedingLookup, $feedDataByDate) {
                $dateKey = $entry->measured_at->format('Y-m-d');
                $feeding = $feedingLookup->get($dateKey);
                $feedData = $feedDataByDate->get($dateKey);

                return (object) [
                    'date' => $dateKey,
                    'weight_avg' => $entry->avg_weight,
                    'weight_min' => $entry->min_weight,
                    'weight_max' => $entry->max_weight,
                    'feed_avg' => $feedData ? (float) $feedData->avg : 0,
                    'feed_min' => $feedData ? (float) $feedData->min : 0,
                    'feed_max' => $feedData ? (float) $feedData->max : 0,
                    'feeding_consumed' => $feeding?->consumed_amount ?? 0,
                ];
            });

            return view('monitoring.superadmin', compact(
                'latestTemperature',
                'latestHumidity',
                'currentTemperature',
                'currentHumidity',
                'temperatureSequence',
                'humiditySequence',
                'sensorData',
                'temperatureSeries',
                'humiditySeries',
                'historicalData',
                'filterTemp',
                'filterHumidity',
                'users',
                'products',
                'salesData',
                'totalRevenue',
                'totalTransactions',
                'totalProductsSold',
                'weightHistory',
                'timelineSeries'
            ));
        }
    }

    public function liveData(Request $request)
    {
        $request->validate([
            'type' => 'required|in:temperature,humidity',
            'index' => 'nullable|integer|min:0',
        ]);

        $type = $request->get('type');
        $index = $request->get('index', 0);
        
        // Generate ordered sequence
        $sequence = $this->generateOrderedSensorSequence($type);
        
        // Get data by index (circular)
        $data = $sequence->get($index % $sequence->count());
        
        // Define ranges for warning check
        $ranges = [
            'temperature' => ['min' => 25, 'max' => 35],
            'humidity' => ['min' => 60, 'max' => 80],
        ];
        $range = $ranges[$type];
        $isWarning = $data['value'] < $range['min'] || $data['value'] > $range['max'];

        return response()->json([
            'value' => $data['value'],
            'status' => $data['status'],
            'unit' => $type === 'temperature' ? '°C' : '%',
            'isWarning' => $isWarning,
            'index' => $index,
            'total' => $sequence->count(),
        ]);
    }

    private function getTelemetrySeries(string $type, string $filter): Collection
    {
        $table = $type === 'temperature' ? 'temperature_readings' : 'humidity_readings';
        $model = $type === 'temperature' ? TemperatureReading::class : HumidityReading::class;

        if (!Schema::hasTable($table)) {
            return $this->fakeTelemetrySeries($type, $filter);
        }

        $query = $model::query()->orderBy('recorded_at', 'ASC');

        if ($filter === 'day') {
            $query->whereDate('recorded_at', today());
        } elseif ($filter === 'week') {
            $query->whereBetween('recorded_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        } elseif ($filter === 'month') {
            $query->whereMonth('recorded_at', now()->month);
        }

        $series = $query->get();

        if ($series->isEmpty()) {
            return $this->fakeTelemetrySeries($type, $filter);
        }

        return $series;
    }

    private function mergeTelemetrySeries(Collection $temperatureSeries, Collection $humiditySeries): Collection
    {
        // Pastikan kedua series sudah diurutkan berdasarkan waktu (ASC)
        $temperatureSeries = $temperatureSeries->sortBy('recorded_at')->values();
        $humiditySeries = $humiditySeries->sortBy('recorded_at')->values();

        $max = max($temperatureSeries->count(), $humiditySeries->count());

        if ($max === 0) {
            return collect();
        }

        // Gabungkan data berdasarkan waktu, pastikan urutan tetap ASC
        $merged = collect(range(0, $max - 1))->map(function ($index) use ($temperatureSeries, $humiditySeries) {
            $temperature = $temperatureSeries[$index] ?? $temperatureSeries->last();
            $humidity = $humiditySeries[$index] ?? $humiditySeries->last();

            // Gunakan waktu dari temperature sebagai referensi utama
            $timeRef = $temperature?->recorded_at ?? $humidity?->recorded_at;

            return [
                'time' => $timeRef ? (is_string($timeRef) ? \Carbon\Carbon::parse($timeRef) : $timeRef)->format('H:i') : '',
                'suhu' => $temperature?->value,
                'kelembapan' => $humidity?->value,
                'recorded_at' => $timeRef,
            ];
        });

        // Pastikan hasil akhir diurutkan berdasarkan waktu
        return $merged->sortBy('recorded_at')->values();
    }

    private function fakeTelemetrySeries(string $type, string $filter): Collection
    {
        $points = match ($filter) {
            'day' => 12,
            'week' => 14,
            default => 30,
        };

        $base = $type === 'temperature' ? 27 : 68;
        $variance = $type === 'temperature' ? 3 : 7;
        $statuses = $type === 'temperature'
            ? ['stabil', 'optimal', 'waspada']
            : ['normal', 'lembap', 'kering'];

        return collect(range(0, $points - 1))->map(function ($offset) use ($base, $variance, $statuses, $points) {
            return (object) [
                'value' => $base + rand(-$variance * 10, $variance * 10) / 10,
                'status' => collect($statuses)->random(),
                'recorded_at' => now()->subHours($points - 1 - $offset),
            ];
        });
    }

    private function getFeedingHistory(?Collection $targetDates = null): Collection
    {
        if (!Schema::hasTable('feeding_logs')) {
            return $this->fakeFeedingHistory();
        }

        $baseQuery = FeedingLog::query()
            ->orderBy('recorded_at', 'ASC');

        $history = (clone $baseQuery)
            ->where('recorded_at', '>=', now()->copy()->subMonths(3)->startOfDay())
            ->get();

        if ($history->isEmpty()) {
            $history = $baseQuery->get();
        }

        if ($history->isEmpty()) {
            return $this->fakeFeedingHistory();
        }

        $aggregated = $history
            ->groupBy(function ($log) {
                return \Carbon\Carbon::parse($log->recorded_at)->format('Y-m-d');
            })
            ->map(function ($logs) {
                $date = \Carbon\Carbon::parse($logs->first()->recorded_at)->startOfDay();

                return (object) [
                    'recorded_at' => $date,
                    'session' => $logs->first()->session,
                    'feed_type' => $logs->first()->feed_type,
                    'offered_amount' => round($logs->avg('offered_amount'), 1),
                    'consumed_amount' => round($logs->avg('consumed_amount'), 1),
                    'cow_weight' => $logs->first()->cow_weight,
                ];
            })
            ->sortBy('recorded_at')
            ->values();

        if ($targetDates && $targetDates->isNotEmpty()) {
            $byDate = $aggregated->keyBy(function ($item) {
                return $item->recorded_at->format('Y-m-d');
            });

            return $targetDates->map(function ($date) use ($byDate) {
                if ($byDate->has($date)) {
                    return $byDate->get($date);
                }

                return (object) [
                    'recorded_at' => \Carbon\Carbon::parse($date),
                    'session' => null,
                    'feed_type' => null,
                    'offered_amount' => 0,
                    'consumed_amount' => 0,
                    'cow_weight' => null,
                ];
            });
        }

        $maxPoints = 6;
        if ($aggregated->count() > $maxPoints) {
            $aggregated = $aggregated->slice(-$maxPoints)->values();
        }

        return $aggregated;
    }

    private function fakeFeedingHistory(): Collection
    {
        $sessions = ['pagi', 'siang', 'sore'];
        $feeds = ['Silase Jagung', 'Rumput Gajah', 'Konsentrat Premium'];

        return collect(range(0, 9))->map(function ($index) use ($sessions, $feeds) {
            return (object) [
                'session' => collect($sessions)->random(),
                'feed_type' => collect($feeds)->random(),
                'offered_amount' => rand(1100, 1400) / 10,
                'consumed_amount' => rand(1000, 1350) / 10,
                'cow_weight' => rand(3900, 4300) / 10,
                'recorded_at' => now()->subDays(9 - $index)->setTime(rand(5, 20), 0),
            ];
        })->sortBy('recorded_at')->values();
    }

    private function randomReading(string $type): object
    {
        $table = $type === 'temperature' ? 'temperature_readings' : 'humidity_readings';
        $model = $type === 'temperature' ? TemperatureReading::class : HumidityReading::class;

        if (!Schema::hasTable($table)) {
            return $this->fakeTelemetrySeries($type, 'day')->random();
        }

        $reading = $model::inRandomOrder()->first();

        if (!$reading) {
            return $this->fakeTelemetrySeries($type, 'day')->random();
        }

        return $reading;
    }

    /**
     * Generate ordered sensor sequence (4 normal, 1 warning, repeat)
     * Data diambil random dari database, lalu diurutkan: 4 normal, 1 warning, dst
     */
    private function generateOrderedSensorSequence(string $type): Collection
    {
        // Define normal ranges
        $ranges = [
            'temperature' => ['min' => 25, 'max' => 35],
            'humidity' => ['min' => 60, 'max' => 80],
        ];
        $range = $ranges[$type];
        
        // Get random data from database (or generate fake if no data)
        $table = $type === 'temperature' ? 'temperature_readings' : 'humidity_readings';
        $model = $type === 'temperature' ? TemperatureReading::class : HumidityReading::class;
        
        $allData = collect();
        
        if (Schema::hasTable($table)) {
            // Get random readings from database
            $readings = $model::inRandomOrder()->limit(50)->get();
            foreach ($readings as $reading) {
                $allData->push([
                    'value' => (float) $reading->value,
                    'status' => $reading->status ?? 'normal',
                ]);
            }
        }
        
        // If not enough data, generate fake data
        if ($allData->count() < 20) {
            $baseValue = $type === 'temperature' ? 30 : 70;
            for ($i = 0; $i < 30; $i++) {
                $value = $baseValue + rand(-100, 100) / 10;
                $allData->push([
                    'value' => round($value, 1),
                    'status' => 'normal',
                ]);
            }
        }
        
        // Separate normal and warning data
        $normalData = $allData->filter(function ($item) use ($range) {
            return $item['value'] >= $range['min'] && $item['value'] <= $range['max'];
        })->shuffle();
        
        $warningData = $allData->filter(function ($item) use ($range) {
            return $item['value'] < $range['min'] || $item['value'] > $range['max'];
        })->shuffle();
        
        // If no warning data, generate some
        if ($warningData->isEmpty()) {
            for ($i = 0; $i < 10; $i++) {
                $warningValue = rand(0, 1) === 0 
                    ? $range['min'] - rand(15, 40) / 10
                    : $range['max'] + rand(15, 40) / 10;
                $warningData->push([
                    'value' => round($warningValue, 1),
                    'status' => $this->getStatusLabel($type, $warningValue, $range),
                ]);
            }
        }
        
        // Order: 4 normal, 1 warning, repeat
        $sequence = collect();
        $normalIndex = 0;
        $warningIndex = 0;
        
        while ($sequence->count() < 50) { // Generate enough for cycling
            // Add 4 normal
            for ($i = 0; $i < 4 && $normalIndex < $normalData->count(); $i++) {
                $item = $normalData->get($normalIndex % $normalData->count());
                $sequence->push([
                    'value' => $item['value'],
                    'status' => $this->getStatusLabel($type, $item['value'], $range),
                    'isWarning' => false,
                ]);
                $normalIndex++;
            }
            
            // Add 1 warning
            if ($warningIndex < $warningData->count()) {
                $item = $warningData->get($warningIndex % $warningData->count());
                $sequence->push([
                    'value' => $item['value'],
                    'status' => $this->getStatusLabel($type, $item['value'], $range),
                    'isWarning' => true,
                ]);
                $warningIndex++;
            } else {
                // If no more warning data, generate one
                $warningValue = rand(0, 1) === 0 
                    ? $range['min'] - rand(15, 40) / 10
                    : $range['max'] + rand(15, 40) / 10;
                $sequence->push([
                    'value' => round($warningValue, 1),
                    'status' => $this->getStatusLabel($type, $warningValue, $range),
                    'isWarning' => true,
                ]);
            }
        }
        
        return $sequence;
    }

    /**
     * Get status label based on value and range
     */
    private function getStatusLabel(string $type, float $value, array $range): string
    {
        if ($value < $range['min']) {
            return $type === 'temperature' ? 'Terlalu Rendah' : 'Terlalu Kering';
        } elseif ($value > $range['max']) {
            return $type === 'temperature' ? 'Terlalu Tinggi' : 'Terlalu Lembap';
        } else {
            return $type === 'temperature' ? 'Stabil' : 'Normal';
        }
    }
}
