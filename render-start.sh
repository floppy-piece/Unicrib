#!/usr/bin/env sh

echo "Fixing storage and cache permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Clearing stale application caches..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache


echo "Running database migrations..."
php artisan migrate --force

echo "Starting Nginx & PHP-FPM..."
exec /start.sh