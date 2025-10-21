# Database Documentation - Aplikasi Manajemen Konveksi

## Struktur Database

Database ini dirancang untuk sistem manajemen konveksi dengan fitur-fitur lengkap untuk mengelola order, produksi, dan invoicing.

### 0. System Tables

-   **sessions** - Laravel session management (untuk menyimpan session pengguna)

### 1. Users & Sales

-   **users** - Pengguna sistem (owner, admin, pm, karyawan)
-   **sales** - Data sales/marketing

### 2. Master Product

-   **product_categories** - Kategori produk (Tshirt, Shirt, Kemeja, dll)
-   **material_categories** - Kategori material (Cotton Combat, Heavy Cotton, dll)
-   **material_textures** - Tekstur material (24s Hydro, super soft, medium)
-   **material_sleeves** - Tipe lengan (None, 3/4, 7/8, Long, Short)
-   **material_sizes** - Ukuran produk (2-3 Thn, 4-5 Th, 8xl) dengan extra_price
-   **shippings** - Metode pengiriman
-   **services** - Layanan tambahan

### 3. Customers & Location

-   **provinces** - Provinsi
-   **cities** - Kota/Kabupaten
-   **districts** - Kecamatan
-   **villages** - Kelurahan/Desa
-   **customers** - Data pelanggan dengan alamat lengkap

### 4. Orders

-   **orders** - Data order utama dengan informasi produk, material, dan status produksi
-   **design_variants** - Variasi desain dalam satu order
-   **order_items** - Detail item order (qty, size, sleeve per design variant)
-   **extra_services** - Layanan tambahan per order

### 5. Invoicing & Payments

-   **invoices** - Invoice untuk setiap order
-   **payments** - History pembayaran (DP, repayment, full_payment)

## Relationships

### Order Flow

```
Customer -> Order -> Design Variants -> Order Items
                  -> Extra Services
                  -> Invoice -> Payments
```

### Location Hierarchy

```
Province -> City -> District -> Village -> Customer
```

## Cara Menjalankan

### 1. Install Dependencies

```bash
composer install
```

### 2. Copy Environment File

```bash
cp .env.example .env
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan koneksi database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stgr_production
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. (Optional) Run Seeders

```bash
php artisan db:seed
```

Atau specific seeder:

```bash
php artisan db:seed --class=UserSeeder
```

### 7. Reset Database (Fresh Migration)

Jika ingin reset dan rebuild database:

```bash
php artisan migrate:fresh --seed
```

## Default Users (dari UserSeeder)

-   **Owner**: username: `owner`, password: `password`
-   **Admin**: username: `admin`, password: `password`
-   **PM**: username: `pm`, password: `password`
-   **Karyawan**: username: `karyawan`, password: `password`

## Field Types Reference

### Enum Fields

-   **users.role**: `'owner', 'admin', 'pm', 'karyawan'`
-   **orders.priority**: `'normal', 'high'`
-   **orders.production_status**: `'pending', 'wip', 'finished', 'cancelled'`
-   **invoices.status**: `'unpaid', 'dp', 'paid'`
-   **payments.payment_method**: `'tranfer', 'cash'`
-   **payments.payment_type**: `'dp', 'repayment', 'full_payment'`

### Decimal Fields (12,2)

Semua field yang berhubungan dengan harga/uang menggunakan DECIMAL(12,2):

-   Subtotal, discount, grand_total
-   Unit price, extra price
-   Amount paid, amount due

## Notes

-   Semua tabel memiliki `created_at` dan `updated_at` timestamps
-   Foreign keys menggunakan `onDelete('cascade')` untuk data transaksional
-   Foreign keys menggunakan `onDelete('set null')` untuk data referensi customer
-   Field unique pada master data untuk mencegah duplikasi
