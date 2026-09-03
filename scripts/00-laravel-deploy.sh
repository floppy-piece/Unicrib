#!/usr/bin/env bash
echo "Running deployment script..."

# Clear and cache configurations
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix storage and cache permissions for the web server user
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

echo "Deployment finished successfully!"