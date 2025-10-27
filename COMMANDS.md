# ==================================

# QUICK COMMANDS REFERENCE

# ==================================

# ✅ DEPLOYMENT COMMANDS

# ----------------------

# Enable maintenance mode

php artisan down --refresh=15

# Disable maintenance mode

php artisan up

# Clear ALL caches

php artisan optimize:clear

# Cache everything (PRODUCTION)

php artisan optimize

# Individual cache commands

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear individual caches

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Install dependencies (PRODUCTION)

composer install --optimize-autoloader --no-dev

# Optimize autoloader

composer dump-autoload --optimize

# Run migrations in production

php artisan migrate --force

# ✅ DEVELOPMENT COMMANDS

# -----------------------

# Run local server

php artisan serve

# Run queue worker

php artisan queue:work

# Run queue in background

php artisan queue:work --daemon

# Clear queue

php artisan queue:clear

# Fresh migration with seed

php artisan migrate:fresh --seed

# Create migration

php artisan make:migration migration_name

# Create controller

php artisan make:controller ControllerName

# Create model

php artisan make:model ModelName

# ✅ DEBUGGING COMMANDS

# ---------------------

# Check routes

php artisan route:list

# Check config

php artisan config:show

# Tinker (Laravel console)

php artisan tinker

# Check environment

php artisan env

# ✅ OPTIMIZATION CHECK

# ---------------------

# Check if config is cached

php artisan config:show app.debug

# Check Laravel version

php artisan --version

# Check installed packages

composer show

# ✅ ASSET COMMANDS

# -----------------

# Install npm dependencies

npm install

# Build for development

npm run dev

# Build for production

npm run build

# Watch for changes

npm run watch

# ✅ PERMISSION COMMANDS (Linux/Mac)

# ----------------------------------

# Set proper permissions

chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs

# Fix ownership

chown -R www-data:www-data storage bootstrap/cache

# ✅ DATABASE COMMANDS

# --------------------

# Backup database

mysqldump -u username -p database_name > backup.sql

# Restore database

mysql -u username -p database_name < backup.sql

# Check database connection

php artisan db:show

# ✅ CACHE STORE TESTING

# ----------------------

# Test cache

php artisan tinker

> Cache::put('test', 'value', 60);
> Cache::get('test');

# ✅ CPANEL SPECIFIC

# ------------------

# Create symbolic link (if needed)

ln -s ../storage/app/public public/storage

# Check PHP version

php -v

# Check loaded extensions

php -m

# ✅ GIT COMMANDS

# ---------------

# Check status

git status

# Add all changes

git add .

# Commit

git commit -m "Your message"

# Push to main

git push origin main

# Pull latest

git pull origin main

# ✅ MONITORING COMMANDS

# ----------------------

# Watch logs

tail -f storage/logs/laravel.log

# Check last 50 lines

tail -n 50 storage/logs/laravel.log

# Search in logs

grep "error" storage/logs/laravel.log

# ✅ PERFORMANCE TESTING

# ----------------------

# Check memory usage

php artisan optimize --memory

# Benchmark artisan commands

time php artisan config:cache

# ✅ COMMON ISSUES FIX

# --------------------

# Storage link broken

php artisan storage:link

# Session issues

php artisan session:clear
php artisan cache:clear

# Route not found after cache

php artisan route:clear
php artisan route:cache

# Config not updated

php artisan config:clear
php artisan config:cache

# 500 Error

-   Check storage/logs/laravel.log
-   Check .env file exists
-   Check APP_KEY is set
-   Check file permissions
-   Check database connection

# 419 Page Expired

-   Clear cache
-   Check session configuration
-   Check CSRF token

# Slow queries

-   Check indexes
-   Use eager loading
-   Enable query log temporarily
