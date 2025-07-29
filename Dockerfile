# Use FrankenPHP official image with PHP 8.3
FROM dunglas/frankenphp:php8.3-alpine

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    zip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev

# Install PHP extensions required by Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        ctype \
        fileinfo \
        gd \
        json \
        mbstring \
        pdo \
        pdo_mysql \
        tokenizer \
        xml \
        zip \
        intl \
        opcache

# Install Redis extension
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /app

# Set proper permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key if not set
RUN php artisan key:generate --ansi

# Cache Laravel configuration and routes
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose ports
EXPOSE 80 443

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Start FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
