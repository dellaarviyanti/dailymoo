<?php

namespace Database\Seeders;

use App\Models\FeedingLog;
use App\Models\HumidityReading;
use App\Models\TemperatureReading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MonitoringDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if tables exist
        if (!Schema::hasTable('temperature_readings') || 
            !Schema::hasTable('humidity_readings') || 
            !Schema::hasTable('feeding_logs')) {
            $this->command->warn('Monitoring tables not found. Run migrations first: php artisan migrate');
            return;
        }

        TemperatureReading::truncate();
        HumidityReading::truncate();
        FeedingLog::truncate();

        // Temperature & humidity for last 24 hours (every hour)
        for ($i = 24; $i >= 0; $i--) {
            $recordedAt = now()->subHours($i);

            TemperatureReading::create([
                'value' => rand(240, 310) / 10,
                'status' => collect(['stabil', 'optimal', 'waspada'])->random(),
                'recorded_at' => $recordedAt,
            ]);

            HumidityReading::create([
                'value' => rand(550, 780) / 10,
                'status' => collect(['normal', 'lembap', 'kering'])->random(),
                'recorded_at' => $recordedAt,
            ]);
        }

        // Feeding logs for last 7 days (pagi & sore)
        $sessions = ['pagi', 'siang', 'sore'];
        $feedTypes = ['Silase Jagung', 'Rumput Gajah', 'Konsentrat Premium'];

        for ($day = 0; $day < 7; $day++) {
            foreach ($sessions as $session) {
                $offered = rand(1100, 1400) / 10;
                $consumed = $offered - rand(0, 80) / 10;

                FeedingLog::create([
                    'session' => $session,
                    'feed_type' => collect($feedTypes)->random(),
                    'offered_amount' => $offered,
                    'consumed_amount' => $consumed,
                    'cow_weight' => rand(3900, 4300) / 10,
                    'recorded_at' => now()->subDays($day)->setTime(rand(5, 20), 0),
                ]);
            }
        }
    }
}

