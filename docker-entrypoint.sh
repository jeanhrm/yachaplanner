#!/bin/bash
echo "=== Running migrations ==="
php artisan migrate --force 2>&1
echo "=== Running seeders ==="
php artisan db:seed --force 2>&1
echo "=== Starting server ==="
exec php artisan serve --host=0.0.0.0 --port=8080