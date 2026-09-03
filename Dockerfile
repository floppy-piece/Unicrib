FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Allow composer to run as root during build
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV PHP_MEMORY_LIMIT 512M

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Write the robust entrypoint script
RUN printf '#!/bin/sh\n\
    echo "Fixing storage permissions..."\n\
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache\n\
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache\n\
    \n\
    echo "Clearing stale application caches..."\n\
    php artisan optimize:clear\n\
    \n\
    echo "Publishing Livewire assets..."\n\
    php artisan livewire:publish --assets --ansi\n\
    \n\
    echo "Running database migrations..."\n\
    php artisan migrate:fresh --force\n\
    \n\
    exec /start.sh\n' > /entrypoint.sh \n\
    && chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]