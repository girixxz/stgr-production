# =======================================
# WINDOWS DEPLOYMENT SCRIPT - PowerShell
# =======================================
# Untuk menjalankan di Windows/Local sebelum push
# Run: .\deploy-windows.ps1

Write-Host "🚀 Starting deployment preparation..." -ForegroundColor Green

# 1. Clear caches
Write-Host "🧹 Clearing caches..." -ForegroundColor Yellow
php artisan optimize:clear

# 2. Run tests (optional)
# Write-Host "🧪 Running tests..." -ForegroundColor Yellow
# php artisan test

# 3. Build frontend assets
Write-Host "🎨 Building frontend assets..." -ForegroundColor Yellow
npm run build

# 4. Generate optimized files
Write-Host "⚡ Generating cache files..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Optimize autoloader
Write-Host "🔧 Optimizing autoloader..." -ForegroundColor Yellow
composer dump-autoload --optimize

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "✨ Preparation completed!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Cyan
Write-Host "   1. Test application locally" -ForegroundColor White
Write-Host "   2. Commit changes: git add . && git commit -m 'Optimized for production'" -ForegroundColor White
Write-Host "   3. Push to server: git push origin main" -ForegroundColor White
Write-Host "   4. Run deploy.sh on server" -ForegroundColor White
