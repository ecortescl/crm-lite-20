#!/bin/bash

echo "==================================="
echo "Instalación de API con Sanctum"
echo "==================================="
echo ""

echo "1. Instalando dependencias de Composer..."
composer require laravel/sanctum darkaonline/l5-swagger

echo ""
echo "2. Ejecutando migraciones..."
php artisan migrate

echo ""
echo "3. Generando documentación Swagger..."
php artisan l5-swagger:generate

echo ""
echo "4. Limpiando caché..."
php artisan config:clear
php artisan route:clear

echo ""
echo "==================================="
echo "✓ Instalación completada"
echo "==================================="
echo ""
echo "Próximos pasos:"
echo "1. Inicia sesión en el CRM"
echo "2. Ve a Settings > API Tokens"
echo "3. Crea un nuevo token"
echo "4. Accede a la documentación en: http://localhost:8000/api/documentation"
echo ""
