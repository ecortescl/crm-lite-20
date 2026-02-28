#!/bin/sh
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Validaciones mínimas para producción
if [ -z "${APP_KEY}" ]; then
    echo "❌ APP_KEY no está configurada. Define APP_KEY en Dokploy."
    exit 1
fi

DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-5432}"
DB_USER="${DB_USERNAME:-laravel}"
DB_NAME="${DB_DATABASE:-laravel}"
DB_PASS="${DB_PASSWORD:-}"

# Esperar a que la base de datos esté lista
echo "⏳ Esperando PostgreSQL..."
until pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USER}" > /dev/null 2>&1; do
    sleep 1
done
echo "✅ PostgreSQL está listo"

# Limpiar cache de Laravel antes de cualquier comando que use DB
# (evita usar credenciales antiguas cacheadas en bootstrap/cache/config.php)
echo "🧹 Limpiando cache de configuración..."
php artisan config:clear
php artisan cache:clear || true

# Validar credenciales reales antes de correr migraciones
echo "🔐 Verificando credenciales de PostgreSQL..."
if ! PGPASSWORD="${DB_PASS}" psql -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USER}" -d "${DB_NAME}" -c "select 1;" > /dev/null 2>&1; then
    echo "❌ No fue posible autenticar en PostgreSQL con DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD."
    echo "   Revisa que DB_PASSWORD (app) coincida con POSTGRES_PASSWORD (db) y que el usuario '${DB_USER}' tenga esa clave."
    exit 1
fi
echo "✅ Credenciales PostgreSQL válidas"

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
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache || echo "⚠️  route:cache omitido (revisa rutas con closures)"
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

# Asegurar permisos de nginx para uploads
echo "🔐 Configurando permisos de Nginx..."
mkdir -p /var/lib/nginx/tmp/client_body
chown -R www-data:www-data /var/lib/nginx
chmod -R 755 /var/lib/nginx

echo "✨ Aplicación lista!"

# Iniciar supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
