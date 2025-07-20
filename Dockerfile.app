# Gunakan image FrankenPHP
FROM dunglas/frankenphp:1-php8.3

# Install Composer
COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

# Install ekstensi PHP
RUN install-php-extensions gd pdo_mysql bcmath exif pcntl zip

# Set direktori kerja
WORKDIR /app

# Salin semua file proyek ke dalam container
COPY . .

# Expose port FrankenPHP
EXPOSE 80 443

