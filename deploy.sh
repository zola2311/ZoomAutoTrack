#!/bin/bash
set -e

echo "=========================================="
echo "  🚀 Zoom EV Production Deployment Script "
echo "=========================================="

# 1. Put application into maintenance mode
echo "1. Enabling maintenance mode..."
php artisan down --render="errors::503" || true

# 2. Install / update Composer dependencies (production only)
echo "2. Installing Composer production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 2.5 Build frontend assets if npm is installed and build is missing
if [ ! -d "public/build" ] && command -v npm &> /dev/null; then
    echo "Building frontend production assets..."
    npm ci || npm install
    npm run build
fi

# 3. Run database migrations
echo "3. Running database migrations..."
php artisan migrate --force

# 4. Clear old caches
echo "4. Clearing old application cache..."
php artisan optimize:clear

# 5. Cache configuration, routes, views, and events for maximum production speed
echo "5. Compiling optimized production caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Ensure public storage symlink exists
echo "6. Checking storage symlink..."
php artisan storage:link || true

# 7. Ensure upload and cache directory permissions
echo "7. Setting required folder permissions..."
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache public/uploads/quotations/voice_notes
chmod -R 775 storage bootstrap/cache public/uploads

# 8. Restart queue workers (if supervisor is running)
if command -v supervisorctl &> /dev/null; then
    echo "8. Restarting background queue workers..."
    supervisorctl restart all || true
fi

# 9. Bring application back online
echo "9. Disabling maintenance mode..."
php artisan up

echo "=========================================="
echo "  ✅ Zoom EV is now live in production!   "
echo "=========================================="
