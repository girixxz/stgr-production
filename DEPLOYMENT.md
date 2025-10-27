# 🚀 PANDUAN DEPLOYMENT KE CPANEL

## 📋 CHECKLIST SEBELUM DEPLOY

-   [ ] Test aplikasi di local environment
-   [ ] Backup database production
-   [ ] Update `.env` untuk production
-   [ ] Build frontend assets (`npm run build`)
-   [ ] Test semua fitur critical
-   [ ] Commit semua perubahan ke Git

---

## 🔧 PERSIAPAN ENVIRONMENT

### 1. Update File `.env`

```env
# WAJIB diubah untuk production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Cache & Session - Gunakan file untuk cPanel
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Database - Sesuaikan dengan cPanel
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Cloudinary tetap sama
CLOUDINARY_URL=cloudinary://466968418586524:Nu0KlXb8n9fEQ-SFhK0AgI1ftNE@drfdmdgoa
CLOUDINARY_CLOUD_NAME=drfdmdgoa
CLOUDINARY_API_KEY=466968418586524
CLOUDINARY_API_SECRET=Nu0KlXb8n9fEQ-SFhK0AgI1ftNE
```

⚠️ **PENTING**: Jangan lupa set `APP_DEBUG=false` di production!

---

## 📦 LANGKAH DEPLOYMENT

### A. Di Local (Windows)

1. **Build Assets**

    ```powershell
    npm install
    npm run build
    ```

2. **Test Production Mode**

    ```powershell
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan serve
    ```

3. **Commit & Push**
    ```powershell
    git add .
    git commit -m "Production ready"
    git push origin main
    ```

### B. Di Server cPanel

1. **Login ke cPanel via SSH/Terminal**

2. **Navigate ke project**

    ```bash
    cd /path/to/your/laravel/project
    ```

3. **Pull Latest Code**

    ```bash
    git pull origin main
    ```

4. **Run Migration**

    ```bash
    # Jalankan migration untuk menambah indexes
    php artisan migrate --force
    ```

5. **Install Dependencies**

    ```bash
    composer install --optimize-autoloader --no-dev
    ```

6. **Set Permissions**

    ```bash
    chmod -R 755 storage bootstrap/cache
    chmod -R 775 storage/logs
    ```

7. **Cache Everything**

    ```bash
    php artisan optimize
    # atau manual:
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    ```

8. **Create Storage Link** (jika belum)

    ```bash
    php artisan storage:link
    ```

9. **Clear Old Cache**

    ```bash
    php artisan cache:clear
    ```

10. **Test Application**
    - Buka website di browser
    - Test login
    - Test create order
    - Check error logs

---

## ⚡ SCRIPT DEPLOYMENT OTOMATIS

### Opsi 1: Gunakan Script Bash (Recommended)

```bash
# Berikan permission execute
chmod +x deploy.sh

# Jalankan script
./deploy.sh
```

### Opsi 2: Manual Commands

```bash
php artisan down
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize
php artisan up
```

---

## 🔍 MONITORING SETELAH DEPLOY

### 1. Check Error Logs

```bash
tail -f storage/logs/laravel.log
```

### 2. Check PHP Errors

-   Lihat error_log di cPanel File Manager
-   Path biasanya: `public_html/error_log`

### 3. Test Critical Features

-   [ ] Login
-   [ ] Create Order
-   [ ] Upload Gambar (Cloudinary)
-   [ ] Generate Invoice
-   [ ] Payment Recording
-   [ ] Task Management

### 4. Monitor Performance

-   Check page load speed
-   Monitor database queries (jika ada query slow, cek indexes)
-   Check memory usage

---

## 🐛 TROUBLESHOOTING

### Error 500 - Internal Server Error

**Solusi:**

1. Check `storage/logs/laravel.log`
2. Pastikan `.env` file ada
3. Check `APP_KEY` sudah di-set
4. Check permissions: `chmod -R 755 storage bootstrap/cache`

```bash
# Generate key jika belum ada
php artisan key:generate
```

### Error 419 - Page Expired

**Solusi:**

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Halaman Lemot

**Solusi:**

1. Check sudah run migration indexes

    ```bash
    php artisan migrate:status
    ```

2. Pastikan cache aktif

    ```bash
    php artisan optimize
    ```

3. Check slow queries di log

    ```bash
    grep "Slow Query" storage/logs/laravel.log
    ```

4. Clear static cache dan warmup
    ```bash
    php artisan cache:clear-static --warmup
    ```

### Database Connection Error

**Solusi:**

1. Check credentials di `.env`
2. Test connection:
    ```bash
    php artisan db:show
    ```

### Upload Gambar Gagal (Cloudinary)

**Solusi:**

1. Check Cloudinary credentials di `.env`
2. Test manual di browser: https://cloudinary.com/console
3. Check file size limit

### Routes Not Found After Update

**Solusi:**

```bash
php artisan route:clear
php artisan route:cache
```

### CSS/JS Not Loading

**Solusi:**

1. Check file ada di `public/build/`
2. Clear browser cache
3. Check `.htaccess` file
4. Run `npm run build` lagi

---

## 📊 OPTIMASI LANJUTAN

### 1. Setup Cron Jobs di cPanel

Untuk Laravel Scheduler (jika digunakan):

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

Untuk Queue Worker:

```
* * * * * cd /path/to/project && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

### 2. Enable OPcache

Edit `php.ini` di cPanel:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 3. Optimize .htaccess

Replace `public/.htaccess` dengan file `.htaccess.optimized` yang sudah disediakan.

### 4. Database Optimization

```sql
-- Jalankan di phpMyAdmin
OPTIMIZE TABLE orders, order_items, customers, invoices, payments;
```

---

## 🔄 UPDATE WORKFLOW

Ketika ada update code:

1. **Local Testing**

    ```powershell
    git pull origin main
    composer install
    npm install && npm run build
    php artisan migrate
    php artisan optimize
    ```

2. **Deploy ke Production**

    ```bash
    ./deploy.sh
    ```

3. **Verify**
    - Test di browser
    - Check logs
    - Monitor errors

---

## 📞 SUPPORT

Jika ada masalah:

1. Check `storage/logs/laravel.log`
2. Check cPanel error logs
3. Lihat TROUBLESHOOTING di atas
4. Check COMMANDS.md untuk referensi command

---

## ✅ CHECKLIST POST-DEPLOYMENT

-   [ ] Website bisa diakses
-   [ ] Login berfungsi
-   [ ] Database terkoneksi
-   [ ] Upload gambar work (Cloudinary)
-   [ ] Create order berfungsi
-   [ ] Payment recording work
-   [ ] Tidak ada error 500
-   [ ] Page load cepat (< 3 detik)
-   [ ] Cache aktif (config, route, view)
-   [ ] Log tidak ada error critical

---

## 🎯 HASIL YANG DIHARAPKAN

Setelah optimasi ini:

-   ⚡ **Page load 3-5x lebih cepat**
-   📉 **Database query berkurang 50-70%**
-   💾 **Memory usage lebih efisien**
-   🚀 **Better user experience**
-   🔒 **More secure (production mode)**

---

**Good Luck! 🚀**
