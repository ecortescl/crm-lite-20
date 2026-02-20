#!/bin/bash

echo "🔍 Diagnóstico de problema de subida de logos..."

# Verificar que estamos en el contenedor correcto
if [ ! -f "artisan" ]; then
    echo "❌ Este script debe ejecutarse desde el directorio raíz de Laravel"
    exit 1
fi

# 1. Verificar directorios
echo ""
echo "📁 Verificando directorios..."
mkdir -p storage/app/public/logos
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# 2. Verificar enlace simbólico
echo ""
echo "🔗 Verificando enlace simbólico..."
if [ -L "public/storage" ]; then
    echo "✅ Enlace simbólico existe"
    ls -la public/storage
else
    echo "⚠️  Enlace simbólico no existe, creándolo..."
    php artisan storage:link
fi

# 3. Configurar permisos
echo ""
echo "🔐 Configurando permisos..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 4. Verificar configuración de filesystem
echo ""
echo "⚙️  Verificando configuración..."
php artisan config:clear
php artisan cache:clear

# 5. Mostrar información del sistema
echo ""
echo "📊 Información del sistema:"
echo "Usuario actual: $(whoami)"
echo "Permisos de storage:"
ls -la storage/app/
echo ""
echo "Permisos de public:"
ls -la public/ | grep storage

# 6. Verificar límites de PHP
echo ""
echo "📝 Límites de PHP:"
php -i | grep -E "upload_max_filesize|post_max_size|memory_limit"

echo ""
echo "✅ Diagnóstico completado"
echo ""
echo "Si el problema persiste, verifica:"
echo "1. Que FILESYSTEM_DISK=public en tu archivo .env"
echo "2. Que APP_URL esté configurado correctamente"
echo "3. Los logs en storage/logs/laravel.log"
