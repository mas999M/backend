#!/bin/sh

echo "⏳ در حال انتظار برای آماده شدن دیتابیس MySQL..."

# بررسی اتصال به MySQL قبل از شروع
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "🔁 در انتظار دیتابیس..."
    sleep 3
done

echo "✅ دیتابیس آماده است!"

# اجرای migrate
echo "🚀 اجرای migrate..."
php artisan migrate --force

# اجرای seeder
echo "🌱 اجرای db:seed..."
php artisan db:seed --force

# اجرای سرور لاراول
echo "✅ اجرای php artisan serve..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
