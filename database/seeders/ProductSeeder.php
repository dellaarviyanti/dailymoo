<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Susu Dailymoo Original',
                'description' => 'Susu segar murni dengan rasa original yang creamy dan kaya nutrisi. Diproduksi langsung dari peternakan sapi perah berkualitas tinggi.',
                'category' => 'Minuman',
                'price' => 15000,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Susu Dailymoo Coklat',
                'description' => 'Nikmati rasa coklat lezat dalam setiap tegukan. Perpaduan sempurna antara susu segar dan coklat premium.',
                'category' => 'Minuman',
                'price' => 16000,
                'stock' => 45,
                'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Susu Dailymoo Stroberi',
                'description' => 'Perpaduan susu segar dan rasa stroberi alami yang menyegarkan. Cocok untuk semua kalangan.',
                'category' => 'Minuman',
                'price' => 17000,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Keju Dailymoo Premium',
                'description' => 'Keju berkualitas tinggi dengan tekstur lembut dan rasa yang kaya. Produk olahan susu terbaik untuk keluarga.',
                'category' => 'Olahan Susu',
                'price' => 45000,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Yogurt Dailymoo Natural',
                'description' => 'Yogurt alami tanpa pemanis buatan, kaya probiotik untuk kesehatan pencernaan. Fresh dan sehat setiap hari.',
                'category' => 'Olahan Susu',
                'price' => 12000,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Butter Dailymoo',
                'description' => 'Butter premium dengan tekstur lembut dan aroma yang menggugah selera. Perfect untuk masakan dan roti.',
                'category' => 'Olahan Susu',
                'price' => 35000,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1588168333986-5078d3ae3976?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pakan Konsentrat Premium',
                'description' => 'Pakan konsentrat berkualitas tinggi untuk sapi perah. Mengandung nutrisi lengkap untuk produksi susu optimal.',
                'category' => 'Pakan',
                'price' => 25000,
                'stock' => 100,
                'image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vitamin Sapi Perah',
                'description' => 'Suplemen vitamin lengkap untuk menjaga kesehatan dan produktivitas sapi perah. Formula khusus untuk laktasi.',
                'category' => 'Suplemen',
                'price' => 75000,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mineral Block',
                'description' => 'Blok mineral untuk melengkapi kebutuhan mineral sapi perah. Meningkatkan kualitas susu dan kesehatan sapi.',
                'category' => 'Suplemen',
                'price' => 55000,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alat Pemerah Susu Manual',
                'description' => 'Alat pemerah susu manual yang praktis dan higienis. Cocok untuk peternakan kecil hingga menengah.',
                'category' => 'Peralatan',
                'price' => 150000,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1570042225831-d98fa7577f2e?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desinfektan Kandang',
                'description' => 'Desinfektan khusus untuk kebersihan kandang sapi. Membunuh bakteri dan virus, menjaga kesehatan sapi.',
                'category' => 'Peralatan',
                'price' => 45000,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tempat Pakan Otomatis',
                'description' => 'Tempat pakan otomatis dengan sistem pengisian otomatis. Praktis dan efisien untuk manajemen pakan.',
                'category' => 'Peralatan',
                'price' => 500000,
                'stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=600&h=600&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
