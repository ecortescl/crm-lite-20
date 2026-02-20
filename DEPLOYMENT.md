# 🚀 Guía de Despliegue

## Despliegue en Dokploy

### Requisitos Previos

- Cuenta en Dokploy
- Repositorio Git del proyecto
- Dominio configurado (opcional, Dokploy puede proporcionar uno)

### Pasos para Desplegar

#### 1. Crear Proyecto en Dokploy

1. Accede a tu panel de Dokploy
2. Crea un nuevo proyecto
3. Selecciona "Docker Compose" o "Dockerfile"
4. Conecta tu repositorio Git

#### 2. Configurar Variables de Entorno

En el panel de Dokploy, configura las siguientes variables de entorno:

**Variables Obligatorias:**

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
DB_PASSWORD=TU_PASSWORD_SEGURA_AQUI
```

**Variables Opcionales:**

```env
# Ejecutar seeders en primer despliegue
RUN_SEEDERS=true

# Configuración de correo (si usas)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario
MAIL_PASSWORD=tu_password
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### 3. Generar APP_KEY

Para generar una APP_KEY válida, ejecuta localmente:

```bash
php artisan key:generate --show
```

O usa este comando Docker:

```bash
docker run --rm php:8.2-cli php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

#### 4. Configurar Dominio

Dokploy asignará automáticamente un dominio o puedes configurar tu propio dominio:

1. En la configuración del proyecto, ve a "Domains"
2. Agrega tu dominio personalizado
3. Configura los registros DNS según las instrucciones de Dokploy
4. Actualiza `APP_URL` con tu dominio

#### 5. Desplegar

1. Haz clic en "Deploy" en Dokploy
2. Dokploy automáticamente:
   - Clonará el repositorio
   - Construirá la imagen Docker
   - Creará los volúmenes necesarios
   - Levantará los contenedores
   - Ejecutará las migraciones
   - Configurará el dominio

#### 6. Verificar Despliegue

Una vez completado el despliegue:

1. Accede a tu dominio
2. Verifica que la aplicación cargue correctamente
3. Revisa los logs en Dokploy si hay problemas

### Configuración de Volúmenes en Dokploy

Dokploy creará automáticamente dos volúmenes persistentes:

- `db-data`: Para la base de datos PostgreSQL
- `storage-data`: Para archivos subidos por usuarios

Estos volúmenes persisten entre despliegues, asegurando que no pierdas datos.

### Actualizaciones

Para actualizar la aplicación:

1. Haz push de tus cambios al repositorio Git
2. En Dokploy, haz clic en "Redeploy"
3. Dokploy reconstruirá y desplegará automáticamente

Si hay nuevas migraciones, se ejecutarán automáticamente durante el despliegue.

### Backup y Restauración

#### Backup de Base de Datos

Desde el panel de Dokploy:

1. Accede al contenedor de la base de datos
2. Ejecuta:

```bash
pg_dump -U laravel laravel > backup.sql
```

#### Backup de Archivos

Los archivos subidos están en el volumen `storage-data`. Dokploy permite descargar volúmenes desde el panel.

#### Restauración

Para restaurar un backup:

1. Accede al contenedor de la base de datos
2. Ejecuta:

```bash
psql -U laravel laravel < backup.sql
```

### Monitoreo

Dokploy proporciona:

- Logs en tiempo real
- Métricas de uso de recursos
- Estado de contenedores
- Health checks automáticos

### Troubleshooting

#### La aplicación no inicia

1. Verifica los logs en Dokploy
2. Asegúrate de que `APP_KEY` esté configurada
3. Verifica que las credenciales de base de datos sean correctas

#### Error 500

1. Revisa los logs de la aplicación
2. Verifica que las migraciones se hayan ejecutado
3. Limpia el cache: accede al contenedor y ejecuta `php artisan cache:clear`

#### Problemas con uploads

1. Verifica que el volumen `storage-data` esté montado
2. Verifica permisos: `chown -R www-data:www-data /var/www/html/storage`

## Despliegue Local con Docker

### Inicio Rápido

```bash
# Usar el script de inicio
./docker-start.sh

# O manualmente
cp .env.docker .env
# Edita .env con tus configuraciones
docker-compose build
docker-compose up -d
```

### Comandos Útiles

```bash
# Ver logs
docker-compose logs -f

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Acceder al contenedor
docker-compose exec app sh

# Detener
docker-compose down
```

### Usando Makefile

```bash
# Ver todos los comandos disponibles
make help

# Instalación completa
make install

# Ver logs
make logs

# Ejecutar migraciones
make migrate

# Backup de base de datos
make backup-db
```

## Configuración de Producción

### Seguridad

1. **APP_DEBUG**: Siempre `false` en producción
2. **APP_KEY**: Genera una clave única y segura
3. **DB_PASSWORD**: Usa contraseñas fuertes
4. **HTTPS**: Configura SSL/TLS (Dokploy lo hace automáticamente)

### Optimización

El Dockerfile ya incluye:

- Cache de configuración, rutas y vistas
- Optimización de autoloader de Composer
- OPcache habilitado
- Compresión gzip
- Cache de assets estáticos

### Escalabilidad

Para escalar la aplicación:

1. En Dokploy, aumenta el número de réplicas
2. Considera usar Redis para cache y sesiones
3. Configura un CDN para assets estáticos

## Soporte

Para problemas o preguntas:

1. Revisa los logs en Dokploy
2. Consulta la documentación de Laravel
3. Revisa `README.Docker.md` para más detalles técnicos
