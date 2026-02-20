# Multi-stage build para optimizar el tamaño de la imagen
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copiar archivos de dependencias
COPY package*.json ./

# Instalar dependencias de Node
RUN npm ci

# Copiar código fuente
COPY . .

# Build de assets
RUN npm run build

# Imagen final
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP
RUN apk add --no-cache \
    postgresql-dev \
    postgresql-client \
    nginx \
    supervisor \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Copiar assets compilados desde el builder
COPY --from=node-builder /app/public/build ./public/build

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Crear directorio para uploads
RUN mkdir -p /var/www/html/storage/app/public \
    && chown -R www-data:www-data /var/www/html/storage/app/public

# Configuración de Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Configuración de PHP-FPM
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Configuración de Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Script de inicio
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
