FROM php:8.2-fpm

# نصب پیش‌نیازها
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libonig-dev libpng-dev libxml2-dev nginx curl \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath

WORKDIR /var/www/html

# کپی پروژه
COPY . .

# نصب Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# دسترسی پوشه‌ها
RUN chown -R www-data:www-data storage bootstrap/cache

# تنظیم nginx
RUN rm /etc/nginx/sites-enabled/default

# expose port
EXPOSE 8000

# start php-fpm + nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
