#!/bin/bash

# ===========================================
# DEPLOYMENT SCRIPT UNTUK CPANEL PRODUCTION
# ===========================================
# Jalankan script ini setelah pull/deploy ke server

echo "🚀 Starting deployment process..."

# 1. Enable Maintenance Mode
echo "📝 Enabling maintenance mode..."
php artisan down --refresh=15 --message="System is being updated. Please wait..."

# 2. Pull latest code (uncomment jika menggunakan git)
# echo "📥 Pulling latest code..."
# git pull origin main

# 3. Install/Update Composer Dependencies
echo "📦 Installing composer dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# 4. Clear all caches
echo "🧹 Clearing old caches..."
php artisan optimize:clear

# 5. Run Database Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 6. Cache everything for production
echo "⚡ Caching configuration, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Optimize autoloader
echo "🔧 Optimizing autoloader..."
composer dump-autoload --optimize

# 8. Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs

# 9. Build frontend assets (jika diperlukan)
# Uncomment jika Anda update frontend
# echo "🎨 Building frontend assets..."
# npm install
# npm run build

# 10. Disable Maintenance Mode
echo "✅ Disabling maintenance mode..."
php artisan up

echo ""
echo "=========================================="
echo "✨ Deployment completed successfully! ✨"
echo "=========================================="
echo ""
echo "📊 Checking application status..."
php artisan --version
echo ""
echo "⚠️  REMINDER: Check application in browser!"
echo "⚠️  Monitor logs: tail -f storage/logs/laravel.log"
