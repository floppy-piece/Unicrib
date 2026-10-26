FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install libc6-compat and curl/tar for Node.js installation
RUN apk update && apk add --no-cache libc6-compat curl tar

# Install Node.js 20.x to satisfy Vite 7 and Tailwind v4 requirements
ENV NODE_VERSION=20.11.0
RUN curl -fsSL https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-linux-x64.tar.xz | tar -xJ -C /usr/local --strip-components=1 \
    && ln -s /usr/local/bin/node /usr/local/bin/nodejs

# Allow composer to run as root during build
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node modules during build stage
RUN npm install

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