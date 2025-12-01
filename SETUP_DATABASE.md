# Panduan Setup Database DailyMoo

## Langkah-langkah Setup Database

### 1. Pastikan Database Sudah Dibuat di phpMyAdmin

1. Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`)
2. Buat database baru dengan nama `dailymoo` (atau sesuai dengan `.env` Anda)
3. Atau gunakan database yang sudah ada

### 2. Konfigurasi `.env`

Pastikan file `.env` sudah dikonfigurasi dengan benar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dailymoo
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:** Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL Anda.

### 3. Jalankan Migration

Buka terminal di folder project dan jalankan:

```bash
php artisan migrate
```

Atau jika menggunakan Laragon, buka terminal Laragon dan jalankan:

```bash
cd "C:\laragon\www\dailymoo - moga fix\dailymoo - Copy"
php artisan migrate
```

### 4. Verifikasi Migration

Untuk melihat status migration:

```bash
php artisan migrate:status
```

### 5. Jika Ada Error

#### Error: Migration sudah dijalankan sebelumnya
Jika ada tabel yang sudah ada, Anda bisa:
- **Opsi 1:** Hapus semua tabel dan jalankan ulang:
  ```bash
  php artisan migrate:fresh
  ```
  ⚠️ **PERINGATAN:** Ini akan menghapus semua data!

- **Opsi 2:** Rollback migration tertentu:
  ```bash
  php artisan migrate:rollback --step=1
  ```

#### Error: Foreign key constraint
Pastikan migration dijalankan dalam urutan yang benar. Laravel akan otomatis menjalankan migration berdasarkan timestamp.

### 6. Tabel yang Akan Dibuat

Setelah migration berhasil, tabel berikut akan dibuat di database:

#### Tabel Utama:
1. ✅ `users` - Data pengguna
2. ✅ `products` - Data produk Shop
3. ✅ `transactions` - Data transaksi
4. ✅ `transaction_items` - Detail item transaksi
5. ✅ `knowledge` - Artikel MooKnowledge
6. ✅ `cow_weights` - History bobot sapi

#### Tabel Tambahan:
7. ✅ `articles` - (kosong, untuk fitur masa depan)
8. ✅ `temperature_readings` - Data suhu
9. ✅ `humidity_readings` - Data kelembaban
10. ✅ `feeding_logs` - Log pemberian pakan
11. ✅ `feeding_history` - History pemberian pakan

#### Tabel Sistem Laravel:
12. ✅ `password_reset_tokens` - Reset password
13. ✅ `sessions` - Session management
14. ✅ `cache` - Cache Laravel
15. ✅ `cache_locks` - Cache locks
16. ✅ `jobs` - Queue jobs
17. ✅ `job_batches` - Batch jobs
18. ✅ `failed_jobs` - Failed jobs

### 7. Verifikasi di phpMyAdmin

Setelah migration selesai, buka phpMyAdmin dan pastikan:

1. Database `dailymoo` (atau sesuai `.env`) sudah dipilih
2. Semua tabel di atas sudah ada di sidebar kiri
3. Struktur tabel sesuai dengan dokumentasi di `DATABASE_STRUCTURE.md`

### 8. Seed Data (Opsional)

Jika Anda ingin menambahkan data contoh, buat seeder:

```bash
php artisan make:seeder DatabaseSeeder
```

Atau jalankan seeder yang sudah ada:

```bash
php artisan db:seed
```

---

## Troubleshooting

### Problem: "Class not found" saat migration
**Solusi:** Jalankan:
```bash
composer dump-autoload
php artisan migrate
```

### Problem: "Access denied" untuk database
**Solusi:** 
1. Pastikan username dan password di `.env` benar
2. Pastikan user MySQL memiliki hak akses ke database

### Problem: "Table already exists"
**Solusi:**
- Jika ingin mempertahankan data: Skip migration yang sudah dijalankan
- Jika ingin reset: Jalankan `php artisan migrate:fresh` (⚠️ Hapus semua data!)

### Problem: "Syntax error" di migration
**Solusi:**
1. Cek file migration yang error
2. Pastikan syntax PHP benar
3. Pastikan versi PHP dan Laravel kompatibel

---

## Catatan Penting

1. **Backup Database:** Sebelum menjalankan `migrate:fresh`, pastikan untuk backup database terlebih dahulu!

2. **Urutan Migration:** Laravel akan otomatis menjalankan migration berdasarkan timestamp di nama file. Pastikan tidak ada konflik.

3. **Foreign Keys:** Beberapa tabel memiliki foreign key constraint. Pastikan tabel yang direferensikan sudah dibuat terlebih dahulu.

4. **Storage Link:** Setelah migration, jalankan juga:
   ```bash
   php artisan storage:link
   ```
   Untuk membuat symlink dari `public/storage` ke `storage/app/public`.

---

**Terakhir diperbarui:** 1 Desember 2025

