# 🚀 Inicio Rápido - Laravel CRM con Docker

## Para Desarrollo Local

### 1️⃣ Verificar Requisitos

```bash
./docker-verify.sh
```

Este script verificará que tengas Docker y Docker Compose instalados.

### 2️⃣ Iniciar la Aplicación

```bash
./docker-start.sh
```

Este script automáticamente:
- Creará el archivo `.env` si no existe
- Generará la `APP_KEY`
- Construirá las imágenes Docker
- Levantará los contenedores
- Ejecutará las migraciones

### 3️⃣ Acceder a la Aplicación

Abre tu navegador en: **http://localhost**

## Para Producción (Dokploy)

### 1️⃣ Preparar Variables de Entorno

Necesitarás configurar estas variables en Dokploy:

```env
APP_NAME=MiCRM
APP_ENV=production
APP_KEY=base64:GENERA_UNA_KEY_AQUI
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=TU_PASSWORD_SEGURA
```

### 2️⃣ Generar APP_KEY

Ejecuta localmente:

```bash
docker run --rm php:8.2-cli php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

Copia el resultado y úsalo como `APP_KEY`.

### 3️⃣ Desplegar en Dokploy

1. Crea un nuevo proyecto en Dokploy
2. Conecta tu repositorio Git
3. Pega las variables de entorno
4. Haz clic en "Deploy"

¡Listo! Dokploy se encargará del resto.

## Comandos Útiles

### Ver Logs

```bash
# Todos los servicios
docker-compose logs -f

# Solo la aplicación
docker-compose logs -f app

# Solo la base de datos
docker-compose logs -f db
```

### Ejecutar Comandos Artisan

```bash
# Migraciones
docker-compose exec app php artisan migrate

# Seeders
docker-compose exec app php artisan db:seed

# Limpiar cache
docker-compose exec app php artisan cache:clear

# Crear usuario
docker-compose exec app php artisan tinker
```

### Acceder al Contenedor

```bash
docker-compose exec app sh
```

### Detener la Aplicación

```bash
docker-compose down
```

### Reiniciar la Aplicación

```bash
docker-compose restart
```

## Usando Make (Más Fácil)

Si tienes `make` instalado:

```bash
# Ver todos los comandos
make help

# Instalación completa
make install

# Ver logs
make logs

# Ejecutar migraciones
make migrate

# Ejecutar seeders
make seed

# Acceder al shell
make shell

# Backup de base de datos
make backup-db

# Detener
make down
```

## Estructura de Archivos Docker

```
.
├── Dockerfile                      # Imagen principal
├── docker-compose.yml              # Orquestación de servicios
├── .env.docker                     # Variables de entorno de ejemplo
├── docker/
│   ├── entrypoint.sh              # Script de inicio
│   ├── nginx/                     # Configuración Nginx
│   ├── php/                       # Configuración PHP
│   └── supervisor/                # Configuración Supervisor
├── docker-start.sh                # Script de inicio rápido
├── docker-verify.sh               # Script de verificación
└── Makefile                       # Comandos útiles
```

## Solución de Problemas Comunes

### La aplicación no inicia

```bash
# Ver logs detallados
docker-compose logs app

# Verificar que PostgreSQL esté listo
docker-compose exec db pg_isready -U laravel
```

### Error de permisos

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Error al subir archivos

Verifica que el volumen esté montado:

```bash
docker-compose exec app ls -la /var/www/html/storage/app/public
```

### Base de datos no conecta

```bash
# Verificar que el contenedor esté corriendo
docker-compose ps

# Reiniciar el contenedor de base de datos
docker-compose restart db
```

### Limpiar todo y empezar de nuevo

```bash
# ⚠️ CUIDADO: Esto eliminará todos los datos
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

## Backup y Restauración

### Backup de Base de Datos

```bash
# Con Make
make backup-db

# Manual
docker-compose exec -T db pg_dump -U laravel laravel > backup.sql
```

### Restaurar Base de Datos

```bash
# Con Make
make restore-db FILE=backup.sql

# Manual
docker-compose exec -T db psql -U laravel laravel < backup.sql
```

### Backup de Archivos Subidos

```bash
# Con Make
make backup-storage

# Manual
docker run --rm -v crm-lite-20_storage-data:/data -v $(pwd):/backup alpine tar czf /backup/storage-backup.tar.gz -C /data .
```

## Configuración Avanzada

### Cambiar Puerto

Edita `.env`:

```env
APP_PORT=8080
```

Luego reinicia:

```bash
docker-compose down
docker-compose up -d
```

### Aumentar Límite de Upload

Edita `docker/php/php.ini` y `docker/nginx/default.conf`, luego reconstruye:

```bash
docker-compose build
docker-compose up -d
```

### Agregar Más Workers de Cola

Edita `docker/supervisor/supervisord.conf` y cambia `numprocs`:

```ini
[program:queue-worker]
numprocs=4  # Aumentar número de workers
```

Luego reconstruye y reinicia.

## Recursos Adicionales

- [README.md](README.md) - Documentación general
- [README.Docker.md](README.Docker.md) - Documentación completa de Docker
- [DEPLOYMENT.md](DEPLOYMENT.md) - Guía de despliegue en Dokploy
- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Docker](https://docs.docker.com)
- [Documentación de Dokploy](https://dokploy.com/docs)

## Soporte

Si tienes problemas:

1. Ejecuta `./docker-verify.sh` para verificar tu configuración
2. Revisa los logs con `docker-compose logs -f`
3. Consulta la documentación en los archivos README
4. Verifica que Docker esté corriendo

---

¡Feliz desarrollo! 🎉
