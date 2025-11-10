FROM php:8.2-cli

WORKDIR /app

COPY . .

# نصب پیش‌نیازها
RUN apt-get update && apt-get install -y unzip git curl libzip-dev zip libonig-dev libpng-dev libxml2-dev \
    && docker-php-ext-install zip pdo_mysql mbstring gd bcmath

# نصب Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# نصب وابستگی‌های لاراول
RUN composer install --no-dev --optimize-autoloader

# دسترسی پوشه‌ها
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# expose port
EXPOSE 8000

# استفاده از PORT محیطی Railway
CMD php artisan migrate --force  && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8000

