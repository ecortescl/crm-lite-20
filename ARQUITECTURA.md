# 🏗️ Arquitectura del Sistema

## Diagrama de Arquitectura Docker

```
┌─────────────────────────────────────────────────────────────┐
│                         DOKPLOY                              │
│  (Gestión automática de dominio, SSL, y orquestación)      │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Docker Compose                            │
│                                                              │
│  ┌────────────────────────────────────────────────────┐    │
│  │           Contenedor: app (Laravel)                 │    │
│  │                                                      │    │
│  │  ┌──────────────────────────────────────────────┐  │    │
│  │  │         Supervisor (Gestor de Procesos)      │  │    │
│  │  │                                              │  │    │
│  │  │  ┌────────────┐  ┌────────────┐  ┌────────┐│  │    │
│  │  │  │   Nginx    │  │  PHP-FPM   │  │ Queue  ││  │    │
│  │  │  │  (Puerto   │  │  (Laravel  │  │Workers ││  │    │
│  │  │  │    80)     │  │   App)     │  │  (x2)  ││  │    │
│  │  │  └────────────┘  └────────────┘  └────────┘│  │    │
│  │  └──────────────────────────────────────────────┘  │    │
│  │                                                      │    │
│  │  Volúmenes:                                         │    │
│  │  • storage-data → /var/www/html/storage/app        │    │
│  │  • logs → /var/www/html/storage/logs               │    │
│  └────────────────────────────────────────────────────┘    │
│                              │                               │
│                              ▼                               │
│  ┌────────────────────────────────────────────────────┐    │
│  │        Contenedor: db (PostgreSQL 16)               │    │
│  │                                                      │    │
│  │  • Base de datos: laravel                          │    │
│  │  • Usuario: laravel                                │    │
│  │  • Health Check: pg_isready                        │    │
│  │                                                      │    │
│  │  Volumen:                                           │    │
│  │  • db-data → /var/lib/postgresql/data              │    │
│  └────────────────────────────────────────────────────┘    │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

## Flujo de Construcción (Build)

```
┌─────────────────────────────────────────────────────────────┐
│                    STAGE 1: Node Builder                     │
│                                                              │
│  1. Copiar package.json y package-lock.json                │
│  2. npm ci (instalar dependencias)                         │
│  3. Copiar código fuente                                   │
│  4. npm run build (compilar assets con Vite)              │
│  5. Generar /public/build                                  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    STAGE 2: PHP Final                        │
│                                                              │
│  1. Instalar extensiones PHP (pdo_pgsql, gd, etc.)        │
│  2. Instalar Composer                                       │
│  3. Copiar código fuente                                   │
│  4. Copiar assets compilados desde Stage 1                 │
│  5. composer install --no-dev --optimize-autoloader        │
│  6. Configurar permisos (www-data)                         │
│  7. Copiar configuraciones (nginx, php, supervisor)        │
│  8. Configurar entrypoint.sh                               │
└─────────────────────────────────────────────────────────────┘
```

## Flujo de Inicio (Runtime)

```
┌─────────────────────────────────────────────────────────────┐
│                    entrypoint.sh                             │
│                                                              │
│  1. ⏳ Esperar a que PostgreSQL esté listo                  │
│  2. 🔗 Crear enlace simbólico: storage → public/storage    │
│  3. 📦 Ejecutar migraciones: php artisan migrate --force   │
│  4. 🌱 Ejecutar seeders (si RUN_SEEDERS=true)              │
│  5. 🧹 Optimizar: config:cache, route:cache, view:cache   │
│  6. 🔐 Configurar permisos de storage y cache              │
│  7. ✨ Iniciar Supervisor                                   │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      Supervisor                              │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  [program:nginx]                                     │  │
│  │  • Servidor web en puerto 80                        │  │
│  │  • Proxy reverso a PHP-FPM                          │  │
│  │  • Gestión de archivos estáticos                    │  │
│  │  • Compresión gzip                                  │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  [program:php-fpm]                                   │  │
│  │  • Procesa requests PHP                             │  │
│  │  • Pool de workers dinámico                         │  │
│  │  • OPcache habilitado                               │  │
│  │  • Límites: 100MB upload, 256MB memoria            │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  [program:queue-worker] (x2)                        │  │
│  │  • Procesa trabajos en cola                         │  │
│  │  • Auto-restart en caso de fallo                    │  │
│  │  • Max 3 intentos por trabajo                       │  │
│  │  • Timeout: 3600 segundos                           │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

## Flujo de Request HTTP

```
Usuario → Dokploy (SSL/Dominio) → Docker (Puerto 80)
                                        │
                                        ▼
                                    Nginx
                                        │
                    ┌───────────────────┴───────────────────┐
                    ▼                                       ▼
            Archivos Estáticos                         PHP-FPM
            (CSS, JS, Imágenes)                            │
                    │                                       ▼
                    │                                   Laravel
                    │                                       │
                    │                   ┌───────────────────┴───────────────┐
                    │                   ▼                                   ▼
                    │              PostgreSQL                          File System
                    │              (Base de Datos)                     (Uploads)
                    │                   │                                   │
                    └───────────────────┴───────────────────────────────────┘
                                        │
                                        ▼
                                  Respuesta HTTP
```

## Gestión de Archivos Subidos

```
┌─────────────────────────────────────────────────────────────┐
│                    Upload de Archivo                         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  1. Usuario sube archivo (max 100MB)                        │
│  2. Nginx recibe el archivo                                 │
│  3. PHP-FPM procesa el request                              │
│  4. Laravel valida y procesa el archivo                     │
│  5. Archivo se guarda en storage/app/public                 │
│  6. Ruta se guarda en PostgreSQL                            │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│              Volumen Persistente: storage-data               │
│                                                              │
│  /var/www/html/storage/app/                                 │
│  ├── public/                                                │
│  │   ├── avatars/                                           │
│  │   ├── documents/                                         │
│  │   └── uploads/                                           │
│  └── ...                                                    │
│                                                              │
│  ✅ Persiste entre despliegues                              │
│  ✅ Backup independiente                                    │
│  ✅ Accesible vía /storage/... en web                       │
└─────────────────────────────────────────────────────────────┘
```

## Volúmenes y Persistencia

```
┌─────────────────────────────────────────────────────────────┐
│                    Volúmenes Docker                          │
│                                                              │
│  ┌────────────────────────────────────────────────────┐    │
│  │  db-data                                            │    │
│  │  • Base de datos PostgreSQL                        │    │
│  │  • Persiste entre reinicios                        │    │
│  │  • Backup: pg_dump                                 │    │
│  └────────────────────────────────────────────────────┘    │
│                                                              │
│  ┌────────────────────────────────────────────────────┐    │
│  │  storage-data                                       │    │
│  │  • Archivos subidos por usuarios                   │    │
│  │  • Persiste entre despliegues                      │    │
│  │  • Backup: tar.gz del volumen                      │    │
│  └────────────────────────────────────────────────────┘    │
│                                                              │
│  ┌────────────────────────────────────────────────────┐    │
│  │  logs (bind mount)                                  │    │
│  │  • Logs de la aplicación                           │    │
│  │  • Accesible desde host                            │    │
│  │  • ./storage/logs                                  │    │
│  └────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

## Stack de Tecnologías

```
┌─────────────────────────────────────────────────────────────┐
│                        Frontend                              │
│                                                              │
│  Vue.js 3 → Inertia.js → Laravel Backend                   │
│     ↓                                                        │
│  TailwindCSS 4 (Estilos)                                    │
│  Radix Vue (Componentes UI)                                 │
│  Lucide Icons (Iconos)                                      │
│  SweetAlert2 (Notificaciones)                               │
│  Vite (Build Tool)                                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                        Backend                               │
│                                                              │
│  Laravel 12 (Framework)                                     │
│  PHP 8.4 (Lenguaje)                                         │
│  Laravel Fortify (Autenticación)                            │
│  Laravel Sanctum (API Tokens)                               │
│  Inertia.js (SSR/SPA Híbrido)                              │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      Base de Datos                           │
│                                                              │
│  PostgreSQL 16                                              │
│  • Tablas: users, roles, permissions, leads, companies     │
│  • Relaciones: Many-to-Many (roles-permissions)            │
│  • Índices optimizados                                      │
└─────────────────────────────────────────────────────────────┘
```

## Configuración de Red

```
┌─────────────────────────────────────────────────────────────┐
│                    Red Docker: app-network                   │
│                                                              │
│  ┌────────────────────────────────────────────────────┐    │
│  │  app:80 ←→ Host:${APP_PORT:-80}                    │    │
│  │  (Expuesto públicamente)                           │    │
│  └────────────────────────────────────────────────────┘    │
│                              │                               │
│                              ▼                               │
│  ┌────────────────────────────────────────────────────┐    │
│  │  db:5432                                            │    │
│  │  (Solo accesible internamente)                     │    │
│  └────────────────────────────────────────────────────┘    │
│                                                              │
│  Comunicación:                                              │
│  • app → db: hostname "db"                                 │
│  • Aislamiento de red                                      │
│  • DNS interno de Docker                                   │
└─────────────────────────────────────────────────────────────┘
```

## Health Checks

```
┌─────────────────────────────────────────────────────────────┐
│                    PostgreSQL Health Check                   │
│                                                              │
│  Comando: pg_isready -U laravel                             │
│  Intervalo: 10 segundos                                     │
│  Timeout: 5 segundos                                        │
│  Reintentos: 5                                              │
│                                                              │
│  Estados:                                                   │
│  • starting → healthy → running                            │
│  • unhealthy → restart automático                          │
└─────────────────────────────────────────────────────────────┘
```

## Optimizaciones

```
┌─────────────────────────────────────────────────────────────┐
│                    Optimizaciones Aplicadas                  │
│                                                              │
│  🚀 Build:                                                  │
│  • Multi-stage build (reduce tamaño de imagen)             │
│  • Alpine Linux (imagen base ligera)                       │
│  • Assets pre-compilados                                   │
│  • Composer optimizado (--no-dev --optimize-autoloader)    │
│                                                              │
│  ⚡ Runtime:                                                │
│  • OPcache habilitado                                      │
│  • Config/Route/View cache                                 │
│  • Gzip compression                                        │
│  • Static file caching (1 año)                             │
│  • PHP-FPM pool dinámico                                   │
│                                                              │
│  📦 Queue:                                                  │
│  • 2 workers por defecto                                   │
│  • Auto-restart en fallos                                  │
│  • Timeout configurable                                    │
└─────────────────────────────────────────────────────────────┘
```

## Seguridad

```
┌─────────────────────────────────────────────────────────────┐
│                    Medidas de Seguridad                      │
│                                                              │
│  🔒 Aplicación:                                             │
│  • APP_DEBUG=false en producción                           │
│  • Secrets en variables de entorno                         │
│  • CSRF protection                                         │
│  • Rate limiting                                           │
│  • Input validation                                        │
│                                                              │
│  🔐 PHP:                                                    │
│  • expose_php=Off                                          │
│  • display_errors=Off                                      │
│  • Session cookies httponly                                │
│                                                              │
│  🛡️ Nginx:                                                 │
│  • Ocultar versión                                         │
│  • Denegar acceso a archivos ocultos                       │
│  • Límite de tamaño de request                             │
│                                                              │
│  🔑 Base de Datos:                                         │
│  • Credenciales en variables de entorno                    │
│  • No expuesta públicamente                                │
│  • Conexión solo desde app container                       │
└─────────────────────────────────────────────────────────────┘
```

---

Esta arquitectura está diseñada para ser:
- ✅ Escalable
- ✅ Mantenible
- ✅ Segura
- ✅ Eficiente
- ✅ Fácil de desplegar
