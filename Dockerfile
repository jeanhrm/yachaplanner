FROM php:8.3-apache

# Extensiones necesarias
RUN apt-get update && apt-get install -y \
    libgd-dev \
    libpq-dev \
    libzip-dev \
    libxml2-dev \
    zip unzip git curl \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        xml \
        zip \
        opcache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Código
WORKDIR /var/www/html
COPY . .

# Instalar dependencias PHP
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Instalar Node y compilar assets
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Apache apunta a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -i 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

EXPOSE 80