FROM php:8.2-cli

WORKDIR /app

COPY . .

# نصب پیش‌نیازها و اکستنشن‌های لازم PHP
RUN apt-get update && apt-get install -y unzip git curl libzip-dev zip libonig-dev libpng-dev libxml2-dev \
    && docker-php-ext-install zip pdo_mysql mbstring gd bcmath

# نصب Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# نصب وابستگی‌های لاراول
RUN composer install --no-dev --optimize-autoloader

# تنظیم مجوزها
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# پورت پیش‌فرض لاراول
EXPOSE 8000

# اضافه کردن اسکریپت راه‌اندازی
COPY ./start.sh /app/start.sh
RUN chmod +x /app/start.sh

# اجرای اسکریپت راه‌اندازی
CMD ["/app/start.sh"]
