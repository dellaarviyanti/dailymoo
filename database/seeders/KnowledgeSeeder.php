<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Knowledge;

class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
            'title' => 'Cara Meningkatkan Produksi Susu Sapi Perah',
            'excerpt' => 'Pelajari teknik dan strategi untuk meningkatkan produksi susu sapi perah dengan metode modern dan teknologi IoT.',
                'content' => 'Produksi susu sapi perah dapat ditingkatkan melalui beberapa pendekatan terpadu:

1. **Manajemen Pakan yang Optimal**
   - Berikan pakan berkualitas tinggi dengan kandungan nutrisi seimbang
   - Pastikan sapi mendapat pakan hijauan segar minimal 3-4 kg per hari
   - Tambahkan konsentrat sesuai kebutuhan produksi susu
   - Gunakan teknologi IoT untuk monitoring konsumsi pakan secara real-time

2. **Kontrol Lingkungan Kandang**
   - Suhu ideal kandang: 18-22°C
   - Kelembaban optimal: 60-70%
   - Ventilasi yang baik untuk sirkulasi udara
   - Pencahayaan yang cukup minimal 16 jam per hari

3. **Program Pemeliharaan Kesehatan**
   - Vaksinasi rutin sesuai jadwal
   - Pemeriksaan kesehatan berkala
   - Manajemen stres yang baik
   - Monitoring kesehatan dengan sensor IoT

4. **Teknologi Digital Monitoring**
   - Gunakan sensor suhu dan kelembaban untuk monitoring 24/7
   - Aplikasi manajemen peternakan untuk tracking produksi
   - Analisis data untuk optimasi produksi

Dengan menerapkan pendekatan ini, produksi susu dapat meningkat hingga 20-30%.',
            'category' => 'Produksi',
            'image' => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=800&h=600&fit=crop',
                'date' => now()->subDays(5)->format('Y-m-d'),
            ],
            [
            'title' => 'Pentingnya Monitoring Suhu di Kandang Sapi',
            'excerpt' => 'Suhu yang optimal sangat penting untuk kesehatan dan produktivitas sapi perah.',
                'content' => 'Suhu kandang merupakan faktor kritis dalam manajemen peternakan sapi perah. Berikut penjelasan lengkapnya:

**Suhu Ideal untuk Sapi Perah:**
- Suhu optimal: 18-22°C
- Suhu di bawah 5°C dapat menyebabkan stres dingin
- Suhu di atas 27°C dapat menyebabkan heat stress yang menurunkan produksi susu

**Dampak Suhu Tidak Optimal:**
1. **Heat Stress (Stres Panas)**
   - Penurunan produksi susu hingga 30%
   - Penurunan kualitas susu
   - Masalah reproduksi
   - Penurunan nafsu makan

2. **Cold Stress (Stres Dingin)**
   - Peningkatan konsumsi pakan untuk menghasilkan panas
   - Penurunan efisiensi pakan
   - Risiko penyakit pernapasan

**Solusi dengan Teknologi IoT:**
- Sensor suhu real-time di berbagai titik kandang
- Sistem alarm otomatis saat suhu tidak normal
- Data logging untuk analisis pola suhu
- Integrasi dengan sistem pendingin/pemanas otomatis

**Tips Praktis:**
- Pasang sensor di ketinggian 1,5 meter dari lantai
- Monitor suhu setiap jam
- Sediakan area teduh dan ventilasi yang baik
- Gunakan kipas atau sistem pendingin saat suhu tinggi

Dengan monitoring suhu yang tepat, kesehatan dan produktivitas sapi dapat terjaga optimal.',
            'category' => 'Kesehatan',
            'image' => 'https://images.unsplash.com/photo-1560493676-04071c5f467b?w=800&h=600&fit=crop',
                'date' => now()->subDays(8)->format('Y-m-d'),
            ],
            [
                'title' => 'Manajemen Pakan yang Efektif untuk Sapi Perah',
                'excerpt' => 'Pakan yang tepat dan seimbang adalah kunci sukses peternakan sapi perah modern.',
                'content' => 'Manajemen pakan yang baik merupakan fondasi utama dalam peternakan sapi perah. Berikut panduan lengkapnya:

**Komposisi Pakan Ideal:**
1. **Hijauan (60-70%)**
   - Rumput gajah, rumput raja, atau rumput odot
   - Minimal 3-4 kg per 100 kg berat badan
   - Pastikan hijauan segar dan tidak layu

2. **Konsentrat (30-40%)**
   - Dedak, jagung, bungkil kedelai
   - Disesuaikan dengan produksi susu
   - Formula: 1 kg konsentrat per 2-3 liter susu

3. **Mineral dan Vitamin**
   - Kalsium, fosfor, magnesium
   - Vitamin A, D, E
   - Garam dapur secukupnya

**Jadwal Pemberian Pakan:**
- Pagi: 05.00-06.00 (40% hijauan + konsentrat)
- Siang: 12.00-13.00 (30% hijauan)
- Sore: 17.00-18.00 (30% hijauan + konsentrat)

**Monitoring dengan Teknologi:**
- Timbangan digital untuk mengukur pakan yang diberikan
- Sensor untuk tracking konsumsi
- Aplikasi untuk analisis efisiensi pakan
- Alert jika konsumsi tidak normal

**Tips Efisiensi:**
- Simpan pakan di tempat kering dan terlindung
- Berikan pakan sedikit demi sedikit untuk mengurangi waste
- Monitor berat badan sapi secara berkala
- Sesuaikan pakan dengan fase produksi (laktasi, kering, bunting)

Dengan manajemen pakan yang tepat, efisiensi produksi dapat meningkat signifikan.',
                'category' => 'Nutrisi',
                'image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=800&h=600&fit=crop',
                'date' => now()->subDays(12)->format('Y-m-d'),
            ],
            [
                'title' => 'Teknologi IoT dalam Peternakan Modern',
                'excerpt' => 'Revolusi digital dalam peternakan: bagaimana IoT mengubah cara kita mengelola peternakan sapi perah.',
                'content' => 'Internet of Things (IoT) telah membawa transformasi besar dalam industri peternakan. Berikut manfaat dan implementasinya:

**Aplikasi IoT di Peternakan:**

1. **Sensor Suhu dan Kelembaban**
   - Monitoring 24/7 kondisi lingkungan kandang
   - Alert otomatis saat kondisi tidak optimal
   - Data historis untuk analisis pola

2. **Sensor Pakan**
   - Tracking konsumsi pakan real-time
   - Deteksi dini masalah kesehatan
   - Optimasi efisiensi pakan

3. **Sensor Kesehatan**
   - Monitoring aktivitas sapi
   - Deteksi gejala penyakit awal
   - Tracking siklus reproduksi

4. **Sistem Manajemen Terintegrasi**
   - Dashboard monitoring real-time
   - Laporan otomatis
   - Analisis data untuk pengambilan keputusan

**Keuntungan Implementasi IoT:**
- Peningkatan efisiensi operasional hingga 25%
- Pengurangan biaya pakan melalui optimasi
- Deteksi dini masalah kesehatan
- Peningkatan produksi susu
- Penghematan waktu dan tenaga kerja

**Langkah Implementasi:**
1. Mulai dengan sensor dasar (suhu, kelembaban)
2. Integrasikan dengan sistem manajemen
3. Latih tim untuk menggunakan teknologi
4. Analisis data secara berkala
5. Skalakan sesuai kebutuhan

**Investasi Awal:**
- Sensor suhu/kelembaban: Rp 500.000 - 2.000.000
- Sistem monitoring: Rp 1.000.000 - 5.000.000
- Maintenance tahunan: 10-15% dari investasi awal

ROI biasanya tercapai dalam 12-18 bulan melalui peningkatan efisiensi dan produksi.',
                'category' => 'Teknologi',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'date' => now()->subDays(15)->format('Y-m-d'),
            ],
            [
                'title' => 'Pencegahan dan Penanganan Mastitis pada Sapi Perah',
                'excerpt' => 'Mastitis adalah penyakit yang paling merugikan dalam peternakan sapi perah. Pelajari cara mencegah dan mengatasinya.',
                'content' => 'Mastitis atau radang ambing merupakan penyakit yang paling sering terjadi dan merugikan dalam peternakan sapi perah.

**Gejala Mastitis:**
- Ambing bengkak, merah, dan panas
- Susu berubah warna (kuning, merah, atau ada gumpalan)
- Produksi susu menurun drastis
- Sapi terlihat sakit dan tidak nafsu makan
- Demam pada kasus akut

**Penyebab Utama:**
1. Bakteri (Staphylococcus, Streptococcus, E. coli)
2. Kebersihan kandang yang buruk
3. Proses pemerahan yang tidak steril
4. Luka pada ambing
5. Stres dan penurunan imunitas

**Pencegahan:**
1. **Kebersihan Kandang**
   - Bersihkan kandang minimal 2x sehari
   - Ganti alas kandang secara berkala
   - Pastikan drainase berfungsi baik

2. **Proses Pemerahan yang Benar**
   - Cuci tangan sebelum memerah
   - Bersihkan ambing dengan air hangat dan desinfektan
   - Keringkan ambing sebelum memerah
   - Gunakan peralatan yang steril
   - Celup puting susu setelah pemerahan (teat dipping)

3. **Manajemen Kesehatan**
   - Vaksinasi rutin
   - Pemeriksaan kesehatan berkala
   - Isolasi sapi yang sakit
   - Monitoring dengan teknologi IoT

**Penanganan:**
1. Isolasi sapi yang terinfeksi
2. Konsultasi dengan dokter hewan
3. Pemberian antibiotik sesuai resep
4. Kompres hangat pada ambing
5. Perah susu lebih sering untuk mengurangi tekanan
6. Berikan pakan bergizi untuk meningkatkan imunitas

**Dampak Ekonomi:**
- Penurunan produksi susu 20-50%
- Kualitas susu menurun (tidak bisa dijual)
- Biaya pengobatan
- Risiko penularan ke sapi lain

Dengan pencegahan yang tepat, insiden mastitis dapat dikurangi hingga 80%.',
                'category' => 'Kesehatan',
                'image' => 'https://images.unsplash.com/photo-1570042225831-d98fa7577f2e?w=800&h=600&fit=crop',
                'date' => now()->subDays(18)->format('Y-m-d'),
            ],
            [
                'title' => 'Manajemen Reproduksi Sapi Perah',
                'excerpt' => 'Strategi manajemen reproduksi yang efektif untuk memastikan kontinuitas produksi dan profitabilitas peternakan.',
                'content' => 'Manajemen reproduksi yang baik sangat penting untuk menjaga kontinuitas produksi susu dan profitabilitas peternakan.

**Siklus Reproduksi Sapi:**
- Siklus estrus: 18-24 hari (rata-rata 21 hari)
- Durasi estrus: 12-18 jam
- Masa bunting: 280-290 hari
- Masa kering: 45-60 hari sebelum melahirkan

**Tanda-tanda Sapi Siap Kawin:**
1. Vulva bengkak dan kemerahan
2. Keluar lendir bening dari vulva
3. Sapi gelisah dan sering mengaum
4. Nafsu makan menurun
5. Sapi menaiki sapi lain atau membiarkan dinaiki

**Teknik Inseminasi Buatan (IB):**
1. **Persiapan:**
   - Identifikasi sapi yang siap kawin
   - Catat waktu munculnya tanda estrus
   - Siapkan semen beku dan peralatan IB

2. **Waktu Inseminasi:**
   - Pagi: IB dilakukan sore hari
   - Sore: IB dilakukan pagi hari berikutnya
   - Prinsip: "AM-PM rule"

3. **Prosedur IB:**
   - Bersihkan area vulva
   - Masukkan pipet IB dengan hati-hati
   - Deposit semen di lokasi yang tepat
   - Catat tanggal dan informasi penting

**Manajemen Sapi Bunting:**
- Pakan berkualitas tinggi
- Hindari stres
- Monitoring kesehatan berkala
- Siapkan kandang untuk melahirkan
- Masa kering minimal 45 hari

**Manajemen Sapi Melahirkan:**
- Siapkan kandang khusus (calving pen)
- Monitor proses kelahiran
- Bantu jika diperlukan (dengan bantuan dokter hewan)
- Pastikan anak sapi mendapat kolostrum dalam 2 jam pertama

**Monitoring dengan Teknologi:**
- Sensor aktivitas untuk deteksi estrus
- Aplikasi untuk tracking siklus reproduksi
- Alert untuk waktu inseminasi optimal
- Database untuk analisis performa reproduksi

**Target Reproduksi:**
- Service per conception: < 2
- Calving interval: 12-13 bulan
- Conception rate: > 50%
- Pregnancy rate: > 20%

Dengan manajemen reproduksi yang tepat, profitabilitas peternakan dapat meningkat signifikan.',
                'category' => 'Reproduksi',
                'image' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=800&h=600&fit=crop',
                'date' => now()->subDays(22)->format('Y-m-d'),
            ],
            [
                'title' => 'Kualitas Air untuk Sapi Perah',
                'excerpt' => 'Air yang berkualitas adalah kebutuhan vital yang sering terabaikan dalam peternakan sapi perah.',
                'content' => 'Air merupakan nutrisi yang paling penting namun sering terabaikan dalam peternakan sapi perah.

**Kebutuhan Air Sapi Perah:**
- Sapi laktasi: 80-120 liter per hari
- Sapi kering: 40-60 liter per hari
- Sapi bunting: 60-80 liter per hari
- Anak sapi: 5-10 liter per hari

**Kualitas Air yang Baik:**
1. **Parameter Fisik:**
   - Jernih, tidak berwarna
   - Tidak berbau
   - Tidak berasa
   - Suhu: 10-20°C (optimal)

2. **Parameter Kimia:**
   - pH: 6,5-8,5
   - Total dissolved solids (TDS): < 1000 ppm
   - Bebas dari logam berat
   - Bebas dari kontaminan berbahaya

3. **Parameter Biologis:**
   - Bebas dari bakteri patogen
   - Total coliform: < 100/100ml
   - E. coli: 0/100ml

**Sumber Air yang Baik:**
- Sumur dalam (lebih baik dari sumur dangkal)
- Air PDAM (jika memenuhi standar)
- Air dari mata air alami
- Hindari air dari sungai yang tercemar

**Manajemen Air:**
1. **Penyimpanan:**
   - Tandon air yang tertutup
   - Bersihkan tandon secara berkala
   - Lindungi dari kontaminasi

2. **Distribusi:**
   - Pastikan air tersedia 24 jam
   - Tempat minum yang mudah dijangkau
   - Bersihkan tempat minum setiap hari
   - Cek aliran air secara berkala

3. **Monitoring:**
   - Uji kualitas air minimal 2x setahun
   - Monitor konsumsi air harian
   - Alert jika konsumsi tidak normal

**Dampak Air Berkualitas Buruk:**
- Penurunan produksi susu
- Masalah kesehatan
- Penurunan nafsu makan
- Masalah reproduksi

**Tips Praktis:**
- Letakkan tempat minum di lokasi strategis
- Pastikan air selalu segar dan dingin
- Tambahkan es batu saat cuaca panas
- Monitor konsumsi dengan teknologi IoT
- Bersihkan tempat minum dengan desinfektan

Investasi dalam kualitas air akan memberikan ROI yang sangat baik melalui peningkatan produksi dan kesehatan sapi.',
                'category' => 'Nutrisi',
                'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop',
                'date' => now()->subDays(25)->format('Y-m-d'),
            ],
            [
                'title' => 'Manajemen Stres Panas (Heat Stress) pada Sapi Perah',
                'excerpt' => 'Heat stress adalah masalah serius yang dapat menurunkan produksi hingga 30%. Pelajari cara mencegah dan mengatasinya.',
                'content' => 'Heat stress atau stres panas merupakan masalah serius yang sering terjadi di iklim tropis seperti Indonesia.

**Penyebab Heat Stress:**
- Suhu udara tinggi (> 27°C)
- Kelembaban tinggi (> 70%)
- Radiasi matahari langsung
- Ventilasi yang buruk
- Kepadatan kandang yang tinggi

**Gejala Heat Stress:**
1. **Fisiologis:**
   - Napas cepat dan dangkal (> 60x/menit)
   - Produksi susu menurun drastis
   - Nafsu makan menurun
   - Peningkatan suhu tubuh (> 39°C)
   - Produksi air liur berlebihan

2. **Perilaku:**
   - Sapi mencari tempat teduh
   - Berdiri lebih lama
   - Mengurangi aktivitas
   - Minum lebih sering

**Dampak Heat Stress:**
- Penurunan produksi susu 20-30%
- Penurunan kualitas susu (lemak dan protein)
- Masalah reproduksi (penurunan fertilitas)
- Penurunan imunitas (rentan penyakit)
- Penurunan berat badan

**Pencegahan dan Penanganan:**

1. **Manajemen Lingkungan:**
   - Pasang atap yang tinggi (minimal 4 meter)
   - Ventilasi yang baik (kipas angin)
   - Sistem pendingin (sprinkler, misting)
   - Area teduh yang cukup
   - Cat atap dengan warna terang

2. **Manajemen Pakan:**
   - Berikan pakan saat pagi dan sore
   - Tambahkan garam untuk mengganti elektrolit
   - Tingkatkan konsentrat (lebih mudah dicerna)
   - Pastikan air minum selalu tersedia dan dingin

3. **Manajemen Waktu:**
   - Aktivitas berat di pagi/sore hari
   - Hindari aktivitas di siang hari (10.00-15.00)
   - Pemerahan di pagi dan sore

4. **Teknologi Monitoring:**
   - Sensor suhu dan kelembaban real-time
   - Alert otomatis saat kondisi kritis
   - Tracking produksi untuk deteksi dini
   - Analisis data untuk optimasi

**Indeks Suhu-Kelembaban (THI):**
- THI < 72: Normal
- THI 72-78: Waspada
- THI 79-83: Berbahaya
- THI > 83: Sangat berbahaya

**Rumus THI:**
THI = (1.8 × T + 32) - [(0.55 - 0.0055 × RH) × (1.8 × T - 26)]

Dimana:
- T = Suhu (°C)
- RH = Kelembaban relatif (%)

Dengan manajemen yang tepat, dampak heat stress dapat diminimalkan hingga 80%.',
                'category' => 'Kesehatan',
                'image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=800&h=600&fit=crop',
                'date' => now()->subDays(28)->format('Y-m-d'),
            ],
        ];

        foreach ($articles as $article) {
            Knowledge::firstOrCreate(
                ['title' => $article['title']],
                $article
            );
        }
    }
}
