#!/usr/bin/env sh

echo "Fixing storage and cache permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cachecache

echo "Clearing stale application caches..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache


echo "Running database migrations..."
php artisan migrate:fresh --force

echo "Starting Nginx & PHP-FPM..."
exec /start.sh