<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Database\Seeders\MonitoringDemoSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo users (skip if already exists)
        User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'superadmin@dailymoo.id',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        User::firstOrCreate(
            ['username' => 'pegawai_demo'],
            [
                'email' => 'pegawai@dailymoo.id',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
            ]
        );

        User::firstOrCreate(
            ['username' => 'pembeli_demo'],
            [
                'email' => 'pembeli@dailymoo.id',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
            ]
        );

        // Create sample users
        User::firstOrCreate(
            ['username' => 'john_doe'],
            [
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
            ]
        );

        User::firstOrCreate(
            ['username' => 'jane_smith'],
            [
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
            ]
        );

        // Create products (lama)
        $products = [
            [
                'name' => 'Pakan Premium 50kg',
                'description' => 'Pakan berkualitas tinggi untuk sapi perah dengan nutrisi lengkap',
                'category' => 'Pakan',
                'price' => 750000,
                'stock' => 50,
            ],
            [
                'name' => 'Sensor IoT Suhu',
                'description' => 'Sensor suhu digital dengan konektivitas WiFi untuk monitoring real-time',
                'category' => 'Sensor IoT',
                'price' => 1200000,
                'stock' => 20,
            ],
            [
                'name' => 'Sensor IoT Kelembapan',
                'description' => 'Sensor kelembapan presisi tinggi untuk kontrol lingkungan kandang',
                'category' => 'Sensor IoT',
                'price' => 1100000,
                'stock' => 25,
            ],
            [
                'name' => 'Vitamin Sapi 1L',
                'description' => 'Suplemen vitamin lengkap untuk meningkatkan kesehatan dan produksi susu',
                'category' => 'Kesehatan',
                'price' => 350000,
                'stock' => 75,
            ],
            [
                'name' => 'Timbangan Digital Pakan',
                'description' => 'Timbangan digital presisi untuk menimbang pakan dengan akurat',
                'category' => 'Aksesori',
                'price' => 2500000,
                'stock' => 15,
            ],
            [
                'name' => 'Pakan Konsentrat 25kg',
                'description' => 'Pakan konsentrat berprotein tinggi untuk sapi perah',
                'category' => 'Pakan',
                'price' => 450000,
                'stock' => 60,
            ],
            [
                'name' => 'Mineral Mix 5kg',
                'description' => 'Campuran mineral penting untuk kesehatan tulang dan produksi susu',
                'category' => 'Kesehatan',
                'price' => 180000,
                'stock' => 100,
            ],
            [
                'name' => 'Robot Feeder Otomatis',
                'description' => 'Robot pemberi pakan otomatis dengan timer dan sensor',
                'category' => 'Teknologi',
                'price' => 15000000,
                'stock' => 5,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }

        // Seeder tambahan
        $this->call([
            ProductSeeder::class,
            KnowledgeSeeder::class,
            MonitoringDemoSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Demo Accounts:');
        $this->command->info('- Superadmin: superadmin@dailymoo.id / password');
        $this->command->info('- Pegawai: pegawai@dailymoo.id / password');
        $this->command->info('- Pembeli: pembeli@dailymoo.id / password');
    }
}
