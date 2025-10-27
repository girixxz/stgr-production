# 🚀 PANDUAN OPTIMASI PRODUCTION - STGR Application

## 📋 DAFTAR OPTIMASI YANG SUDAH DITERAPKAN

### 1. ✅ ENVIRONMENT CONFIGURATION (.env)

### 2. ✅ DATABASE OPTIMIZATION

### 3. ✅ CACHING STRATEGY

### 4. ✅ QUERY OPTIMIZATION

### 5. ✅ VIEW CACHING

### 6. ✅ ASSET OPTIMIZATION

### 7. ✅ SERVER CONFIGURATION

---

## 1️⃣ ENVIRONMENT SETUP PRODUCTION

Update `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Cache Configuration - PENTING!
CACHE_STORE=file  # Gunakan 'file' di cPanel
SESSION_DRIVER=file  # Lebih cepat daripada database
QUEUE_CONNECTION=database

# Database - Pastikan optimized
DB_CONNECTION=mysql
```

⚠️ **WAJIB**: Set `APP_DEBUG=false` di production!

---

## 2️⃣ DATABASE OPTIMIZATION

### A. Tambah Index untuk Query Cepat

Jalankan migration ini untuk menambah index:

```bash
php artisan make:migration add_indexes_for_optimization
```

### B. Optimize Database Connection

Di `config/database.php` untuk MySQL:

-   ✅ Persistent connection sudah tidak digunakan (baik)
-   ✅ Charset utf8mb4 sudah benar

---

## 3️⃣ CACHING STRATEGY

### Cache Config Files

```bash
# Di server production, jalankan sekali:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Clear Cache (ketika update)

```bash
php artisan optimize:clear
# atau individual:
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 4️⃣ QUERY OPTIMIZATION - EAGER LOADING

Masalah N+1 Query sudah diperbaiki di:

-   ✅ OrderController (with relationships)
-   ⚠️ Perlu optimize controllers lain

Gunakan `with()` untuk semua relasi yang dibutuhkan!

---

## 5️⃣ CLOUDINARY OPTIMIZATION

Untuk upload gambar lebih cepat:

-   Resize gambar sebelum upload
-   Gunakan lazy loading untuk image
-   Set transformation di Cloudinary

---

## 6️⃣ LARAVEL PERFORMANCE COMMANDS

### Production Optimization Commands:

```bash
# 1. Install dependencies production only
composer install --optimize-autoloader --no-dev

# 2. Cache everything
php artisan optimize

# 3. Generate autoload files
composer dump-autoload --optimize
```

### Monitoring Performance:

```bash
# Check routes yang slow
php artisan route:list

# Debug query di development
DB::enableQueryLog();
// your code
dd(DB::getQueryLog());
```

---

## 7️⃣ CPANEL SPECIFIC OPTIMIZATION

### .htaccess Optimization

Di `public/.htaccess` tambahkan:

```apache
# Enable GZIP Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Browser Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### PHP Configuration (php.ini)

```ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 20M
post_max_size = 25M
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

---

## 8️⃣ PAGINATION OPTIMIZATION

Sudah bagus menggunakan `paginate(15)` - jangan gunakan `all()` atau `get()` untuk data besar!

---

## 9️⃣ ASSET OPTIMIZATION

### Build Production Assets:

```bash
# Install dependencies
npm install

# Build untuk production (minified)
npm run build
```

File hasil build ada di `public/build/` - commit ke git!

---

## 🔟 MAINTENANCE MODE

Ketika update production:

```bash
php artisan down --refresh=15  # Enable maintenance
# Deploy your changes
php artisan up  # Disable maintenance
```

---

## 📊 MONITORING & DEBUGGING PRODUCTION

### Log Files

```bash
# Check error logs
tail -f storage/logs/laravel.log
```

### Database Query Monitoring

Install Laravel Debugbar (hanya development):

```bash
composer require barryvdh/laravel-debugbar --dev
```

---

## ⚡ QUICK OPTIMIZATION CHECKLIST

Sebelum deploy ke production:

-   [ ] Set `APP_ENV=production` dan `APP_DEBUG=false`
-   [ ] Run `composer install --optimize-autoloader --no-dev`
-   [ ] Run `php artisan optimize`
-   [ ] Run `npm run build`
-   [ ] Tambah database indexes
-   [ ] Setup cron job untuk queue:work (jika pakai queue)
-   [ ] Test semua fitur di staging environment
-   [ ] Setup backup database otomatis
-   [ ] Monitor error logs setelah deploy

---

## 🔧 CRON JOBS (Setup di cPanel)

Tambahkan cron job untuk Laravel scheduler:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

Untuk queue worker (jika pakai background jobs):

```bash
* * * * * cd /path/to/your/project && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

---

## 🎯 EXPECTED IMPROVEMENTS

Setelah optimasi:

-   ⚡ Page load 3-5x lebih cepat
-   📉 Database query berkurang 50-70%
-   💾 Memory usage lebih efisien
-   🚀 Better user experience

---

## 📞 TROUBLESHOOTING

### Error "419 Page Expired"

-   Clear cache: `php artisan cache:clear`
-   Check session config

### Slow Page Load

-   Check `storage/logs/laravel.log`
-   Enable query log temporarily
-   Check database indexes

### Memory Limit Error

-   Increase `memory_limit` di php.ini
-   Optimize query dengan pagination

---

## 🔄 UPDATE WORKFLOW

1. Test di local
2. Push ke repository
3. `php artisan down` di production
4. Pull latest code
5. `composer install --no-dev --optimize-autoloader`
6. `php artisan migrate --force`
7. `php artisan optimize`
8. `php artisan up`

---

✅ **DONE!** Ikuti panduan ini untuk optimasi maksimal!
