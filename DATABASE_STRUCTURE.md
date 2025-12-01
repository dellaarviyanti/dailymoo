# Struktur Database DailyMoo

Dokumentasi lengkap struktur database untuk aplikasi DailyMoo.

## Tabel Utama

### 1. `users`
Tabel untuk menyimpan data pengguna (superadmin, pegawai, pembeli).

**Kolom:**
- `id` (bigint, primary key, auto increment)
- `username` (string, unique)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `role` (enum: 'superadmin', 'pegawai', 'pembeli', default: 'pembeli')
- `remember_token` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Migration:** `2024_01_01_000000_create_users_table.php`

---

### 2. `products`
Tabel untuk menyimpan data produk di Shop.

**Kolom:**
- `id` (bigint, primary key, auto increment)
- `name` (string)
- `description` (text, nullable)
- `category` (string, nullable)
- `price` (decimal(10,2))
- `stock` (integer, default: 0)
- `image` (text, nullable) - **Diubah dari string ke text untuk URL panjang**
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Migration:**
- `2024_01_01_000001_create_products_table.php` (create table)
- `2025_12_01_005300_update_products_image_column.php` (ubah image ke text)

---

### 3. `transactions`
Tabel untuk menyimpan data transaksi pembelian.

**Kolom:**
- `id` (bigint, primary key, auto increment)
- `user_id` (bigint, foreign key ke users.id, cascade delete)
- `customer_name` (string, nullable)
- `customer_address` (text, nullable)
- `customer_phone` (string, nullable)
- `total_amount` (decimal(12,2))
- `status` (enum: 'pending', 'pending_payment', 'payment_verification', 'processing', 'shipped', 'completed', 'cancelled', default: 'pending')
- `payment_proof` (string, nullable) - Path ke file bukti pembayaran
- `bank_account` (string, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Migration:**
- `2024_01_01_000002_create_transactions_table.php` (create table)
- `2025_11_19_000800_add_customer_and_payment_fields_to_transactions_table.php` (tambah kolom customer & payment)

---

### 4. `transaction_items`
Tabel untuk menyimpan detail item dalam setiap transaksi.

**Kolom:**
- `id` (bigint, primary key, auto increment)
- `transaction_id` (bigint, foreign key ke transactions.id, cascade delete)
- `product_id` (bigint, foreign key ke products.id, cascade delete)
- `quantity` (integer)
- `price` (decimal(12,2)) - Harga per item saat transaksi
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Migration:** `2024_01_01_000003_create_transaction_items_table.php`

**Catatan:** Kolom `subtotal` dihitung sebagai `quantity * price` di aplikasi.

---

### 5. `knowledge`
Tabel untuk menyimpan artikel MooKnowledge.

**Kolom:**
- `id` (bigint, primary key, auto increment)
- `title` (string)
- `excerpt` (text)
- `content` (longText)
- `category` (string)
- `image` (string) - Path ke file gambar atau URL
- `date` (date)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Migration:** `2025_11_12_133734_create_knowledge_table.php`

---

### 6. `cow_weights`
Tabel untuk menyimpan history data bobot sapi (ML Prediction).

**Kolom:**
- `id` (bigint, primary key, auto increment)
- `cow_id` (integer) - Nomor sapi (1-10)
- `weight` (decimal(8,2)) - Bobot dalam kg
- `measured_at` (date) - Tanggal pengukuran
- `notes` (text, nullable) - Catatan tambahan
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Index:** `['cow_id', 'measured_at']`

**Migration:** `2025_11_19_000700_create_cow_weights_table.php`

---

## Tabel Tambahan (Opsional)

### 7. `articles`
Tabel kosong (mungkin untuk fitur masa depan).

**Kolom:**
- `id` (bigint, primary key, auto increment)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Migration:** `2025_11_18_113348_create_articles_table.php`

---

### 8. `temperature_readings`
Tabel untuk menyimpan data suhu (jika digunakan).

**Migration:** `2025_11_19_000600_create_temperature_readings_table.php`

---

### 9. `humidity_readings`
Tabel untuk menyimpan data kelembaban (jika digunakan).

**Migration:** `2025_11_19_000601_create_humidity_readings_table.php`

---

### 10. `feeding_logs`
Tabel untuk menyimpan log pemberian pakan (jika digunakan).

**Migration:** `2025_11_19_000602_create_feeding_logs_table.php`

---

## Tabel Sistem Laravel

### 11. `password_reset_tokens`
Tabel untuk reset password.

**Kolom:**
- `email` (string, primary key)
- `token` (string)
- `created_at` (timestamp, nullable)

**Migration:** `2024_01_01_000000_create_users_table.php` (dalam file yang sama)

---

### 12. `sessions`
Tabel untuk session management.

**Kolom:**
- `id` (string, primary key)
- `user_id` (bigint, nullable, indexed)
- `ip_address` (string(45), nullable)
- `user_agent` (text, nullable)
- `payload` (longText)
- `last_activity` (integer, indexed)

**Migration:** `2024_01_01_000000_create_users_table.php` (dalam file yang sama)

---

### 13. `cache`
Tabel untuk cache Laravel.

**Migration:** `0001_01_01_000001_create_cache_table.php`

---

### 14. `cache_locks`
Tabel untuk cache locks.

**Migration:** `0001_01_01_000001_create_cache_table.php`

---

### 15. `jobs`
Tabel untuk queue jobs.

**Migration:** `0001_01_01_000002_create_jobs_table.php`

---

### 16. `job_batches`
Tabel untuk batch jobs.

**Migration:** `0001_01_01_000002_create_jobs_table.php`

---

### 17. `failed_jobs`
Tabel untuk failed jobs.

**Migration:** `0001_01_01_000002_create_jobs_table.php`

---

## Tabel yang Sudah Dihapus

### `sensor_data`
Tabel ini sudah dihapus melalui migration `2025_01_20_000000_drop_sensor_data_table.php`.

---

## Cara Menjalankan Migration

### 1. Jalankan semua migration yang belum dijalankan:
```bash
php artisan migrate
```

### 2. Cek status migration:
```bash
php artisan migrate:status
```

### 3. Rollback migration terakhir:
```bash
php artisan migrate:rollback
```

### 4. Rollback semua migration:
```bash
php artisan migrate:reset
```

### 5. Refresh database (rollback + migrate):
```bash
php artisan migrate:refresh
```

### 6. Fresh migration (drop semua + migrate):
```bash
php artisan migrate:fresh
```

---

## Relasi Database

### Foreign Keys:
1. `transactions.user_id` → `users.id` (CASCADE DELETE)
2. `transaction_items.transaction_id` → `transactions.id` (CASCADE DELETE)
3. `transaction_items.product_id` → `products.id` (CASCADE DELETE)

---

## Catatan Penting

1. **Kolom `image` di `products`** sudah diubah dari `string(255)` ke `text` untuk menampung URL panjang.
2. **Status transaksi** sudah diperluas dengan `pending_payment` dan `payment_verification`.
3. **Kolom customer** (name, address, phone) ditambahkan untuk menyimpan data pembeli.
4. **Payment proof** disimpan sebagai path file di storage.
5. **Semua gambar** (products, knowledge, payment proof) disimpan di `storage/app/public/` dan diakses via route khusus.

---

## File Storage

Gambar dan file disimpan di:
- **Products:** `storage/app/public/products/`
- **Knowledge:** `storage/app/public/knowledge/`
- **Payment Proof:** `storage/app/public/payment-proofs/`

File diakses via route:
- Products: `/products/{id}/image`
- Knowledge: `/knowledge/{id}/image`
- Payment Proof: `/payment-proof/{transaction_id}`

---

## Database yang Digunakan

Aplikasi menggunakan **MySQL/MariaDB** (default Laravel).

Konfigurasi ada di `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dailymoo
DB_USERNAME=root
DB_PASSWORD=
```

---

**Terakhir diperbarui:** 1 Desember 2025

