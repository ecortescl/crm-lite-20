# Imagen base con PHP y Node.js
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema, extensiones de PHP y Node.js
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
    nodejs \
    npm \
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

# Crear .env temporal para el build
RUN cp .env.docker .env && \
    sed -i 's/APP_KEY=/APP_KEY=base64:dGVtcG9yYXJ5a2V5Zm9yYnVpbGRvbmx5MTIzNDU2Nzg5MA==/' .env

# Instalar dependencias de PHP primero
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de Node.js (incluyendo devDependencies para el build)
RUN npm ci

# Build de assets (con variable para deshabilitar Wayfinder durante build)
ENV DOCKER_BUILD=true
RUN npm run build

# Limpiar archivos temporales y node_modules
RUN rm -rf node_modules .env

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
