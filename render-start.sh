#!/usr/bin/env bash

echo "Fixing storage and cache permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache



# Ensure Vite manifest exists at runtime (failsafe if container volume overwrote public/build)
if [ ! -f "public/build/manifest.json" ]; then
    echo "Vite manifest not found. Building assets on the fly..."
    npm run build
fi

echo "Clearing stale application caches..."
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations safely
php artisan migrate --force
php artisan schedule:work

echo "Starting Nginx and PHP-FPM..."
exec /start.sh