FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (needed to compile Vite assets)
RUN apt-get update && apt-get install -y curl \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Allow composer to run as root during build
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build Tailwind / Vite frontend assets
RUN npm install && npm run build

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