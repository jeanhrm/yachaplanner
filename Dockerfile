FROM php:8.4-apache

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

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -i 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' \
    /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN a2dismod mpm_event mpm_worker && a2enmod mpm_prefork

EXPOSE 80