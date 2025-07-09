# Use the official PHP 8.2 FPM image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application code
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader



# Set correct permissions for storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port expected by Render
EXPOSE 10000

# Start Laravel’s built-in server
CMD bash -c "php artisan storage:link && php artisan serve --host=0.0.0.0 --port=10000"
