#!/bin/sh
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando PostgreSQL..."
until pg_isready -h db -U ${DB_USERNAME:-laravel} > /dev/null 2>&1; do
    sleep 1
done
echo "✅ PostgreSQL está listo"

# Crear directorios necesarios para uploads
echo "📁 Creando directorios de storage..."
mkdir -p /var/www/html/storage/app/public/logos
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs

# Crear enlace simbólico para storage si no existe
if [ ! -L /var/www/html/public/storage ]; then
    echo "🔗 Creando enlace simbólico para storage..."
    php artisan storage:link
fi

# Ejecutar migraciones
echo "📦 Ejecutando migraciones..."
php artisan migrate --force

# Generar rutas de Wayfinder
echo "🗺️  Generando rutas de Wayfinder..."
php artisan wayfinder:generate || echo "⚠️  Wayfinder generation skipped"

# Limpiar y optimizar cache
echo "🧹 Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar seeders si es primera vez (opcional)
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --force
fi

# Asegurar permisos correctos
echo "🔐 Configurando permisos..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "✨ Aplicación lista!"

# Iniciar supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
