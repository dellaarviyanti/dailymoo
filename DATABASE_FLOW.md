# Flow Sistem Database DailyMoo

Dokumentasi lengkap tentang alur data dan flow sistem database dalam aplikasi DailyMoo.

---

## 📊 Diagram Relasi Database

```
┌─────────────┐
│    users    │
│─────────────│
│ id (PK)     │
│ username    │◄──────────┐
│ email       │           │
│ password    │           │
│ role        │           │
└─────────────┘           │
                          │
                          │ user_id (FK)
                          │
┌─────────────────────────┴──────────────┐
│          transactions                   │
│─────────────────────────────────────────│
│ id (PK)                                 │
│ user_id (FK) ───────────────────────────┘
│ customer_name                           │
│ customer_address                        │
│ customer_phone                          │
│ total_amount                            │
│ status                                  │
│ payment_proof                           │
│ bank_account                            │
└─────────────┬──────────────────────────┘
              │
              │ transaction_id (FK)
              │
┌─────────────▼──────────────────────────┐
│      transaction_items                  │
│─────────────────────────────────────────│
│ id (PK)                                 │
│ transaction_id (FK) ────────────────────┘
│ product_id (FK) ────────────────────────┐
│ quantity                                │
│ price                                   │
└─────────────────────────────────────────┘
              │
              │ product_id (FK)
              │
┌─────────────▼─────────────┐
│      products             │
│───────────────────────────│
│ id (PK)                  │
│ name                     │
│ description              │
│ category                 │
│ price                    │
│ stock                    │
│ image                    │
└───────────────────────────┘

┌───────────────────────────┐
│      knowledge            │
│───────────────────────────│
│ id (PK)                   │
│ title                     │
│ excerpt                   │
│ content                   │
│ category                  │
│ image                     │
│ date                      │
└───────────────────────────┘

┌───────────────────────────┐
│      cow_weights          │
│───────────────────────────│
│ id (PK)                   │
│ cow_id                    │
│ weight                    │
│ measured_at               │
│ notes                     │
└───────────────────────────┘
```

---

## 🔄 Flow Sistem Secara Keseluruhan

### 1. **Flow Autentikasi & User Management**

```
User Registration/Login
    ↓
[Controller: AuthController]
    ↓
Validasi Input (username, email, password)
    ↓
Hash Password (bcrypt)
    ↓
INSERT INTO users
    ↓
Session Created
    ↓
User Logged In
```

**Tabel yang terlibat:**
- `users` - Menyimpan data user
- `sessions` - Menyimpan session aktif

---

### 2. **Flow Shop (E-Commerce)**

#### A. **Menampilkan Produk**

```
User membuka halaman Shop
    ↓
[ShopController@index]
    ↓
SELECT * FROM products
    ↓
Tampilkan produk di view
```

**Tabel yang terlibat:**
- `products` - Data produk

---

#### B. **Menambah Produk ke Keranjang (Cart)**

```
User klik "Tambah Keranjang"
    ↓
AJAX Request → [CartController@add]
    ↓
Validasi: product_id, quantity
    ↓
SELECT stock FROM products WHERE id = product_id
    ↓
Cek: stock >= quantity?
    ├─ NO → Return error "Stok tidak mencukupi"
    └─ YES → Continue
    ↓
Ambil cart dari Session
    ↓
Cek: product sudah ada di cart?
    ├─ YES → Update quantity (quantity += new_quantity)
    └─ NO → Tambah item baru ke cart
    ↓
Session::put('cart', cart)
    ↓
Return JSON: success, cart_count
```

**Storage:**
- **Session** (bukan database) - Menyimpan cart sementara
- Format: `['product_id' => ['quantity' => 2]]`

**Tabel yang terlibat:**
- `products` - Cek stok

---

#### C. **Checkout & Membuat Transaksi**

```
User klik "Checkout" di Cart
    ↓
[ShopController@checkout]
    ↓
Ambil cart dari Session
    ↓
Filter selected_items (checkbox)
    ↓
Hitung subtotal = Σ(product.price × quantity)
    ↓
Hitung total = subtotal + shipping_fee (Rp 30.000)
    ↓
Tampilkan form checkout
    ↓
─────────────────────────────────────
User isi form & submit
    ↓
[ShopController@processCheckout]
    ↓
Validasi: customer_name, address, phone
    ↓
Cek stock untuk setiap item
    ├─ Stock tidak cukup → Return error
    └─ Stock cukup → Continue
    ↓
BEGIN TRANSACTION
    ↓
INSERT INTO transactions
    (user_id, customer_name, customer_address, 
     customer_phone, total_amount, status='pending_payment')
    ↓
Untuk setiap item di cart:
    ├─ INSERT INTO transaction_items
    │   (transaction_id, product_id, quantity, price)
    └─ UPDATE products SET stock = stock - quantity
    ↓
COMMIT TRANSACTION
    ↓
Hapus selected_items dari Session cart
    ↓
Redirect ke halaman transaksi
```

**Tabel yang terlibat:**
- `transactions` - Data transaksi
- `transaction_items` - Detail item transaksi
- `products` - Update stok

---

#### D. **Upload Bukti Pembayaran**

```
User upload bukti pembayaran
    ↓
[ShopController@uploadPayment]
    ↓
Validasi: file image, max 2MB
    ↓
Store file ke: storage/app/public/payment-proofs/
    ↓
UPDATE transactions
    SET payment_proof = 'payment-proofs/filename.jpg',
        status = 'payment_verification'
    WHERE id = transaction_id
    ↓
Redirect dengan success message
```

**Storage:**
- File disimpan di: `storage/app/public/payment-proofs/`
- Path disimpan di: `transactions.payment_proof`

**Tabel yang terlibat:**
- `transactions` - Update payment_proof & status

---

#### E. **Verifikasi Pembayaran (Admin)**

```
Admin buka halaman Payment Verification
    ↓
[ShopController@paymentVerification]
    ↓
SELECT * FROM transactions
    WHERE status = 'payment_verification'
    ↓
Tampilkan daftar transaksi
    ↓
─────────────────────────────────────
Admin klik "Terima" atau "Tolak"
    ↓
[ShopController@verifyPayment] atau [ShopController@rejectPayment]
    ↓
Jika Terima:
    UPDATE transactions SET status = 'processing'
    ↓
Jika Tolak:
    ├─ UPDATE transactions SET status = 'cancelled'
    └─ UPDATE products SET stock = stock + quantity
       (kembalikan stok)
    ↓
Redirect dengan success message
```

**Tabel yang terlibat:**
- `transactions` - Update status
- `products` - Kembalikan stok (jika ditolak)
- `transaction_items` - Untuk mendapatkan quantity

---

### 3. **Flow MooKnowledge (Artikel)**

#### A. **Menampilkan Artikel**

```
User membuka halaman MooKnowledge
    ↓
[KnowledgeController@index]
    ↓
SELECT * FROM knowledge ORDER BY date DESC
    ↓
Tampilkan artikel di view
```

**Tabel yang terlibat:**
- `knowledge` - Data artikel

---

#### B. **Menambah Artikel Baru (Admin)**

```
Admin klik "Tambah Artikel"
    ↓
Form muncul (modal)
    ↓
Admin isi form & submit
    ↓
[KnowledgeController@store]
    ↓
Validasi: title, excerpt, content, category, date
    ↓
Cek: image_file atau image_url?
    ├─ image_file → Upload ke storage/app/public/knowledge/
    └─ image_url → Simpan URL langsung
    ↓
INSERT INTO knowledge
    (title, excerpt, content, category, image, date)
    ↓
Redirect dengan success message
```

**Storage:**
- File disimpan di: `storage/app/public/knowledge/`
- Path/URL disimpan di: `knowledge.image`

**Tabel yang terlibat:**
- `knowledge` - Data artikel

---

#### C. **Mengedit Artikel (Admin)**

```
Admin klik "Edit" pada artikel
    ↓
Form muncul dengan data existing
    ↓
Admin ubah data & submit
    ↓
[KnowledgeController@update]
    ↓
Validasi input
    ↓
Cek: image_file baru?
    ├─ YES → Upload file baru, hapus file lama
    └─ NO → Cek image_url baru?
        ├─ YES → Update image_url
        └─ NO → Keep current image
    ↓
UPDATE knowledge
    SET title = ..., excerpt = ..., ...
    WHERE id = article_id
    ↓
Redirect dengan success message
```

**Tabel yang terlibat:**
- `knowledge` - Update data artikel

---

### 4. **Flow ML Prediction (Bobot Sapi)**

#### A. **Input Data & Prediksi**

```
User buka halaman Atur Bobot Pakan
    ↓
User input: bobot sapi (kg), umur (hari), setuju/tidak
    ↓
User klik "Prediksi"
    ↓
[WeightController@predictBK]
    ↓
Validasi input
    ↓
Jalankan Python script: predict.py
    ├─ Input: bobot, umur
    └─ Output: prediksi jumlah pakan (kg)
    ↓
Tampilkan hasil prediksi di view
```

**Tabel yang terlibat:**
- Tidak ada (hanya perhitungan ML)

---

#### B. **Simpan History Bobot**

```
User klik "Simpan History"
    ↓
[WeightController@store]
    ↓
Validasi: weights[], dates[]
    ↓
Untuk setiap sapi (1-10):
    INSERT INTO cow_weights
        (cow_id, weight, measured_at, notes)
    ↓
Redirect dengan success message
```

**Tabel yang terlibat:**
- `cow_weights` - History bobot sapi

---

#### C. **Menampilkan History**

```
User buka halaman History Data Bobot Sapi
    ↓
[WeightController@index]
    ↓
SELECT * FROM cow_weights
    ORDER BY measured_at DESC, cow_id ASC
    ↓
Paginate (10 items per page)
    ↓
Tampilkan di tabel
```

**Tabel yang terlibat:**
- `cow_weights` - History bobot sapi

---

### 5. **Flow Admin Dashboard (Shop)**

#### A. **Menampilkan Dashboard**

```
Admin buka halaman Shop
    ↓
[ShopController@index]
    ↓
Cek: role = superadmin atau pegawai?
    ├─ YES → Tampilkan dashboard
    └─ NO → Skip dashboard
    ↓
[ShopController@getAdminDashboardData]
    ↓
Query analytics:
    ├─ Total produk: SELECT COUNT(*) FROM products
    ├─ Stok rendah: SELECT * FROM products WHERE stock < 10
    ├─ Penjualan hari ini: 
    │   SELECT SUM(total_amount) FROM transactions
    │   WHERE status IN ('completed', 'processing', 'shipped')
    │   AND DATE(created_at) = CURDATE()
    ├─ Penjualan bulan ini: (sama, tapi MONTH)
    ├─ Daily sales (7 hari): GROUP BY DATE(created_at)
    ├─ Monthly sales (12 bulan): GROUP BY MONTH(created_at)
    ├─ Best selling: 
    │   SELECT product_id, SUM(quantity) as total
    │   FROM transaction_items
    │   GROUP BY product_id
    │   ORDER BY total DESC
    │   LIMIT 10
    └─ Pending verifications:
        SELECT * FROM transactions
        WHERE status = 'payment_verification'
    ↓
Return data ke view
    ↓
Render charts dengan Chart.js
```

**Tabel yang terlibat:**
- `products` - Total produk, stok rendah
- `transactions` - Data penjualan
- `transaction_items` - Best selling products

---

## 🔗 Relasi Database

### 1. **One-to-Many: User → Transactions**

```
1 User dapat memiliki banyak Transactions
1 Transaction dimiliki oleh 1 User

users.id (1) ──→ transactions.user_id (many)
```

**Implementasi:**
- `User::transactions()` - hasMany
- `Transaction::user()` - belongsTo

---

### 2. **One-to-Many: Transaction → TransactionItems**

```
1 Transaction dapat memiliki banyak TransactionItems
1 TransactionItem dimiliki oleh 1 Transaction

transactions.id (1) ──→ transaction_items.transaction_id (many)
```

**Implementasi:**
- `Transaction::items()` - hasMany
- `TransactionItem::transaction()` - belongsTo

---

### 3. **One-to-Many: Product → TransactionItems**

```
1 Product dapat muncul di banyak TransactionItems
1 TransactionItem merujuk ke 1 Product

products.id (1) ──→ transaction_items.product_id (many)
```

**Implementasi:**
- `Product::transactionItems()` - hasMany
- `TransactionItem::product()` - belongsTo

---

## 💾 Storage System

### 1. **File Storage**

```
storage/app/public/
├── products/          → Gambar produk
├── knowledge/         → Gambar artikel
└── payment-proofs/    → Bukti pembayaran
```

**Cara Akses:**
- Via Route: `/products/{id}/image`
- Via Route: `/knowledge/{id}/image`
- Via Route: `/payment-proof/{transaction_id}`

**Controller Methods:**
- `ShopController@showProductImage()`
- `KnowledgeController@showImage()`
- `ShopController@showPaymentProof()`

---

### 2. **Session Storage**

**Cart:**
```php
Session::put('cart', [
    'product_id' => [
        'quantity' => 2
    ]
]);
```

**Checkout Selected Items:**
```php
Session::put('checkout_selected_items', [1, 2, 3]);
```

---

## 🔄 Status Flow Transaksi

```
pending
    ↓
pending_payment (setelah checkout)
    ↓
payment_verification (setelah upload bukti)
    ↓
processing (setelah admin verifikasi)
    ↓
shipped (setelah admin kirim)
    ↓
completed (setelah diterima pembeli)

ATAU

payment_verification
    ↓
cancelled (jika admin tolak)
```

---

## 📝 Catatan Penting

### 1. **Cart System**
- Cart **TIDAK** disimpan di database
- Cart disimpan di **Session** (temporary)
- Cart akan hilang jika:
  - User logout
  - Session expired
  - Browser cache cleared

### 2. **Stock Management**
- Stock dikurangi saat **checkout** (bukan saat add to cart)
- Stock dikembalikan jika transaksi **ditolak**
- Stock tidak dikembalikan jika transaksi **dibatalkan** oleh user

### 3. **Transaction Items**
- Harga disimpan di `transaction_items.price` (snapshot)
- Jika harga produk berubah, harga di transaksi tetap sama

### 4. **Image Handling**
- Prioritas: File upload > URL > Current image
- File disimpan di `storage/app/public/`
- Diakses via route khusus (tidak perlu storage symlink)

### 5. **Foreign Key Constraints**
- `transactions.user_id` → CASCADE DELETE
- `transaction_items.transaction_id` → CASCADE DELETE
- `transaction_items.product_id` → CASCADE DELETE

---

## 🎯 Summary Flow Utama

### **E-Commerce Flow:**
```
Browse Products → Add to Cart (Session) → Checkout → 
Create Transaction → Upload Payment → Admin Verify → 
Update Status → Complete
```

### **Content Management Flow:**
```
Admin Login → Create/Edit Article → Upload Image → 
Save to Database → Display to Users
```

### **ML Prediction Flow:**
```
Input Data → Run Python Script → Get Prediction → 
Display Result → Save to Database (optional)
```

---

**Terakhir diperbarui:** 1 Desember 2025

