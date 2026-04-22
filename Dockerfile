FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    libgd-dev \
    libpq-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    zip unzip git curl \
    && docker-php-ext-install \
        gd pdo pdo_pgsql pgsql mbstring xml zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --optimize-autoloader --no-dev --no-interaction

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080


COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
CMD ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]