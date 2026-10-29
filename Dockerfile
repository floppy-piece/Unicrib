# Stage 1: Grab Node.js 20 compiled specifically for Alpine (musl libc)
FROM node:20-alpine AS node_builder

# Stage 2: Main Application Image
FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Node.js and npm binaries from the Alpine-based node builder stage
COPY --from=node_builder /usr/local/bin/node /usr/local/bin/node
COPY --from=node_builder /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# Install libc6-compat (required for native Tailwind v4 oxide binaries on Alpine)
RUN apk update && apk add --no-cache libc6-compat

# Allow composer to run as root during build
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-dev --optimize-autoloader --no-interaction && php artisan storage:link && php artisan config:cache && php artisan route:cache

# Build Tailwind / Vite frontend assets inside the container
RUN npm install && npm run build \
    && php artisan livewire:publish --assets \
    && ln -s /var/www/html/public/vendor/livewire /var/www/html/public/livewire

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

# Copy custom start script and make it executable
COPY render-start.sh /render-start.sh
RUN chmod +x /render-start.sh

CMD ["/render-start.sh"]