# Login Credentials - Test Users

Setelah menjalankan `php artisan db:seed`, berikut adalah credentials untuk login:

## Default Test Users

| Role     | Username   | Password   | Fullname      |
| -------- | ---------- | ---------- | ------------- |
| Owner    | `owner`    | `password` | Owner User    |
| Admin    | `admin`    | `password` | Admin User    |
| PM       | `pm`       | `password` | PM User       |
| Karyawan | `karyawan` | `password` | Karyawan User |

## Additional Users

Terdapat **10 random users** tambahan yang dibuat oleh factory dengan:

-   **Username**: Random username
-   **Password**: `password` (sama untuk semua)
-   **Role**: Random (admin, pm, atau karyawan)
-   **Phone**: Random phone number

## Login ke Aplikasi

Gunakan salah satu username dan password di atas untuk login ke aplikasi di:

```
http://stgr-production.test
```

## Reset & Seed Ulang

Jika ingin reset database dan seed ulang:

```bash
# Reset database dan jalankan semua migrations + seeders
php artisan migrate:fresh --seed

# Atau hanya seed ulang (tanpa reset migrations)
php artisan db:seed
```

## Troubleshooting

Jika lupa password atau ingin mengubah password user tertentu, gunakan tinker:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
$user = \App\Models\User::where('username', 'owner')->first();
$user->password = bcrypt('password_baru');
$user->save();
```
