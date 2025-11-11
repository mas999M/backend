#!/bin/sh

echo "⏳ در حال انتظار برای آماده شدن دیتابیس MySQL..."

# بررسی اتصال تا زمانی که دیتابیس آماده بشه
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --port="$DB_PORT" --silent; do
    echo "🔁 منتظر اتصال به دیتابیس در $DB_HOST:$DB_PORT ..."
    sleep 3
done

echo "✅ دیتابیس آماده است!"

# اجرای migrate و seed
echo "🚀 اجرای migrate..."
php artisan migrate --force

echo "🌱 اجرای db:seed..."
php artisan db:seed --force

# اجرای سرور لاراول
echo "✅ اجرای php artisan serve..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
