#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

while ! ncat -z database 3306; do
    echo "Waiting for database..."
    sleep 1
done

echo "Run artisan migrations and cache clear"
php artisan key:generate
php artisan migrate
php artisan cache:clear
php artisan config:clear
php artisan route:clear
exec "$@"



