# 🐳 Configuración Docker para Laravel CRM

Este proyecto está configurado para ejecutarse en Docker con PostgreSQL, optimizado para Dokploy.

## 📋 Características

- ✅ PHP 8.4 con FPM
- ✅ PostgreSQL 16
- ✅ Nginx como servidor web
- ✅ Supervisor para gestión de procesos
- ✅ Queue workers automáticos
- ✅ Soporte completo para uploads de archivos (hasta 100MB)
- ✅ Configuración dinámica de dominio y puerto
- ✅ Volúmenes persistentes para base de datos y archivos
- ✅ Health checks para PostgreSQL
- ✅ Optimización de cache y assets
- ✅ Build optimizado sin Wayfinder durante construcción (se genera en runtime)

## 🚀 Despliegue Local

### 1. Configurar variables de entorno

```bash
# Copiar el archivo de ejemplo
cp .env.docker .env

# Generar APP_KEY
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php artisan key:generate --show
```

Edita `.env` y configura:
- `APP_NAME`: Nombre de tu aplicación
- `APP_URL`: URL de tu aplicación
- `APP_PORT`: Puerto donde se expondrá (default: 80)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Credenciales de PostgreSQL

### 2. Construir y levantar contenedores

```bash
# Construir la imagen
docker-compose build

# Levantar los servicios
docker-compose up -d

# Ver logs
docker-compose logs -f
```

### 3. Acceder a la aplicación

Abre tu navegador en: `http://localhost` (o el puerto configurado en `APP_PORT`)

## 🔧 Despliegue en Dokploy

### Configuración en Dokploy

1. **Crear nuevo proyecto** en Dokploy

2. **Configurar variables de entorno** en Dokploy:

```env
APP_NAME=MiCRM
APP_ENV=production
APP_KEY=base64:TU_KEY_GENERADA_AQUI
APP_DEBUG=false
APP_URL=https://tu-dominio.com
APP_PORT=80

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=TU_PASSWORD_SEGURA_AQUI

# Opcional: ejecutar seeders en primer despliegue
RUN_SEEDERS=true
```

3. **Configurar dominio** en Dokploy:
   - Dokploy asignará automáticamente el dominio
   - El puerto se configura dinámicamente

4. **Desplegar**:
   - Dokploy construirá la imagen automáticamente
   - Los volúmenes se crearán automáticamente
   - Las migraciones se ejecutarán automáticamente

## 📁 Gestión de Archivos Subidos

Los archivos subidos se almacenan en un volumen persistente:

```bash
# Ver archivos en el volumen
docker-compose exec app ls -la /var/www/html/storage/app/public

# Backup de archivos
docker run --rm -v crm-lite-20_storage-data:/data -v $(pwd):/backup alpine tar czf /backup/storage-backup.tar.gz -C /data .

# Restaurar archivos
docker run --rm -v crm-lite-20_storage-data:/data -v $(pwd):/backup alpine tar xzf /backup/storage-backup.tar.gz -C /data
```

## 🛠️ Comandos Útiles

### Ejecutar comandos Artisan

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Acceder al contenedor

```bash
docker-compose exec app sh
```

### Ver logs

```bash
# Logs de todos los servicios
docker-compose logs -f

# Logs de la aplicación
docker-compose logs -f app

# Logs de PostgreSQL
docker-compose logs -f db

# Logs internos de Laravel
docker-compose exec app tail -f storage/logs/laravel.log
```

### Reiniciar servicios

```bash
# Reiniciar todo
docker-compose restart

# Reiniciar solo la app
docker-compose restart app
```

### Limpiar y reconstruir

```bash
# Detener y eliminar contenedores
docker-compose down

# Eliminar también volúmenes (⚠️ CUIDADO: elimina la base de datos)
docker-compose down -v

# Reconstruir desde cero
docker-compose build --no-cache
docker-compose up -d
```

## 🔒 Seguridad

- Los archivos `.env` no se incluyen en la imagen Docker
- Las credenciales se pasan como variables de entorno
- PHP está configurado para no exponer su versión
- Los uploads están limitados a 100MB
- Los logs de errores no se muestran al usuario

## 📊 Monitoreo

### Health Check de PostgreSQL

El servicio de base de datos incluye un health check que verifica su disponibilidad cada 10 segundos.

### Logs estructurados

Todos los servicios escriben logs en:
- `/var/www/html/storage/logs/` (dentro del contenedor)
- Accesibles vía `docker-compose logs`

## 🔄 Actualizaciones

Para actualizar la aplicación:

```bash
# Pull del código actualizado
git pull

# Reconstruir la imagen
docker-compose build

# Aplicar cambios
docker-compose up -d

# Ejecutar migraciones si es necesario
docker-compose exec app php artisan migrate --force
```

## 🐛 Troubleshooting

### La aplicación no inicia

```bash
# Ver logs detallados
docker-compose logs app

# Verificar que PostgreSQL esté listo
docker-compose exec db pg_isready -U laravel
```

### Problemas con permisos de archivos

```bash
# Reconfigurar permisos
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Error al subir archivos

Verifica que:
1. El volumen `storage-data` esté montado correctamente
2. Los permisos sean correctos (775)
3. El límite de tamaño esté configurado (100MB por defecto)

### Base de datos no conecta

```bash
# Verificar que el contenedor de DB esté corriendo
docker-compose ps

# Verificar logs de PostgreSQL
docker-compose logs db

# Probar conexión manual
docker-compose exec app psql -h db -U laravel -d laravel
```

## 📝 Notas Adicionales

- El proyecto usa Alpine Linux para imágenes más ligeras
- Multi-stage build para optimizar el tamaño final
- Supervisor gestiona PHP-FPM, Nginx y Queue Workers
- Los assets se compilan durante el build
- Cache de configuración, rutas y vistas se genera automáticamente
