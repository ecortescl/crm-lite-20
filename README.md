# CRM Lite

Sistema CRM simplificado para gestión de prospectos (leads), empresas, cotizaciones y equipos de venta. Construido con **Laravel 12**, **Vue.js 3** e **Inertia.js** como SPA de servidor, con soporte completo para Docker y despliegue en Dokploy.

---

## Tabla de Contenidos

- [Características](#características)
- [Stack Tecnológico](#stack-tecnológico)
- [Arquitectura](#arquitectura)
- [Módulos del Sistema](#módulos-del-sistema)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Variables de Entorno](#variables-de-entorno)
- [Docker](#docker)
- [Despliegue en Dokploy](#despliegue-en-dokploy)
- [Scripts Disponibles](#scripts-disponibles)
- [Testing](#testing)
- [Seguridad](#seguridad)

---

## Características

| Módulo | Descripción |
|---|---|
| 🔐 Autenticación | Login, registro, recuperación de contraseña y 2FA (TOTP) con Laravel Fortify |
| 👥 Usuarios, Roles y Permisos | Sistema RBAC con roles personalizables (`jefatura`, `vendedor`) y permisos por recurso |
| 🏢 Empresas | CRUD de empresas con RUT chileno, giro, industria, tamaño y relación con leads |
| 📊 Leads (Prospectos) | Gestión completa con tracking UTM, estados configurables, reuniones y seguimiento |
| 📋 Kanban | Vista Kanban de leads con paginación por columna y filtro de fechas |
| 📄 Cotizaciones | Creación de cotizaciones con ítems, subtotal, IVA, validez y exportación a PDF |
| 📅 Calendario | Vista de leads con reuniones agendadas |
| 📈 Dashboard | Métricas de conversión, embudo de ventas y resumen de cotizaciones |
| ⚙️ Configuración | Ajustes del sistema (nombre de empresa, logo, moneda) |
| 🐳 Docker | Imagen multi-stage optimizada con PHP-FPM, Nginx, Supervisor y PostgreSQL |

---

## Stack Tecnológico

### Backend
| Paquete | Versión | Rol |
|---|---|---|
| PHP | 8.4+ | Lenguaje principal |
| Laravel | 12 | Framework |
| Inertia.js (Laravel) | 2 | Adaptador SPA sin API REST |
| Laravel Fortify | 1 | Autenticación headless |
| Laravel Sanctum | 4 | Tokens de API |
| Laravel Wayfinder | 0 | Rutas tipadas en TypeScript |
| barryvdh/laravel-dompdf | 3 | Exportación de cotizaciones a PDF |
| Pest | 4 | Framework de testing |
| PostgreSQL | 16 | Base de datos principal |

### Frontend
| Paquete | Versión | Rol |
|---|---|---|
| Vue.js | 3 | Framework reactivo |
| Inertia.js (Vue) | 2 | Routing SPA con SSR |
| TailwindCSS | 4 | Estilos utilitarios |
| Radix Vue / Reka UI | 1 / 2 | Componentes UI accesibles (shadcn/ui) |
| @tanstack/vue-table | 8 | Tablas con filtros, ordenamiento y paginación |
| Lucide Vue | — | Íconos |
| SweetAlert2 | 11 | Notificaciones y modales |
| VueUse | 12 | Composables utilitarios |
| vue-sonner | 2 | Toasts/notificaciones |
| Vite | 7 | Bundler y dev server |

---

## Arquitectura

```
crm-lite-20/
├── app/
│   ├── Actions/            # Acciones de Fortify (auth flows)
│   ├── Concerns/           # Traits compartidos
│   ├── Http/
│   │   ├── Controllers/    # Controladores de la aplicación
│   │   │   ├── Api/        # Endpoints de API REST
│   │   │   └── Settings/   # Controladores de configuración
│   │   ├── Middleware/     # Middleware personalizados
│   │   └── Requests/       # Form Requests con validación
│   ├── Models/             # Modelos Eloquent
│   └── Providers/          # Service Providers
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   ├── seeders/            # Seeders (estados, roles, permisos)
│   └── factories/          # Factories para testing
├── resources/
│   ├── css/                # Estilos globales (app.css)
│   ├── js/
│   │   ├── pages/          # Páginas Inertia (Vue SFC)
│   │   ├── components/     # Componentes reutilizables
│   │   ├── layouts/        # Layouts de la aplicación
│   │   ├── composables/    # Composables Vue
│   │   ├── types/          # Definiciones TypeScript
│   │   ├── lib/            # Utilidades (cn, etc.)
│   │   └── routes/         # Rutas Wayfinder (auto-generadas)
│   └── views/              # Blade (solo app.blade.php)
├── routes/
│   ├── web.php             # Rutas web principales
│   ├── auth.php            # Rutas de autenticación
│   └── api.php             # Rutas de API
├── docker/                 # Configuración de contenedores
│   ├── nginx/              # Nginx config
│   ├── php/                # php.ini y pools FPM
│   └── supervisor/         # Supervisord (queue workers)
├── tests/                  # Tests (Pest)
├── Dockerfile              # Imagen multi-stage
├── docker-compose.yml      # Orquestación local
└── Makefile                # Comandos Make para Docker
```

---

## Módulos del Sistema

### 📊 Dashboard
Vista de resumen con métricas clave calculadas en tiempo real:
- **Conteo por estado**: número de leads en cada etapa del embudo.
- **Tasa de conversión**: leads concretados vs. descartados.
- **Tasa de negociación**: leads en etapa de negociación.
- **Reuniones agendadas**: porcentaje de leads con reunión programada.
- **Total cotizado**: suma de todas las cotizaciones activas.
- **Mini-Kanban**: vista rápida de los últimos 5 leads por estado.

Los usuarios con rol `jefatura` ven métricas globales; los demás solo ven sus leads asignados.

### 📊 Leads
Gestión completa del ciclo de vida del prospecto:
- Información de contacto: nombre, email, teléfono, empresa.
- **Tracking UTM** completo: `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`.
- **Reuniones**: fecha, link (videollamada), notas y registro del agendador.
- **Información comercial**: presupuesto, ítems del presupuesto, monto final, estado de pago (`pending`, `partial`, `paid`).
- **Vista tabla**: búsqueda en tiempo real, filtros por estado, origen y agente asignado. Paginación de 15 registros.
- **Vista Kanban**: tablero arrastrable con paginación por columna (5 por columna) y filtro de rango de fechas.

### 📄 Cotizaciones
Módulo completo de presupuestos con:
- Numeración automática (`COT-YYYY-XXXX`).
- Datos del cliente: nombre, RUT, email, teléfono, dirección.
- Ítems con descripción, cantidad, precio unitario y subtotal.
- Cálculo automático de subtotal, IVA (tasa configurable) y total.
- Estados: `borrador`, `enviada`, `aceptada`, `rechazada`.
- Fechas de emisión y vencimiento.
- Exportación a **PDF** con DomPDF.
- Vinculación a un lead específico.

### 🏢 Empresas
- Razón social, RUT chileno (con formato automático `XX.XXX.XXX-X`), nombre de fantasía, giro.
- Categorización por industria y tamaño.
- Información de contacto: email, teléfono, sitio web, dirección (comuna, ciudad, región).
- Relación con múltiples leads.

### 👥 Usuarios, Roles y Permisos
- **RBAC** (Role-Based Access Control): roles con múltiples permisos, usuarios con múltiples roles.
- Rol `jefatura` (equivalente a admin): acceso total a todos los registros.
- Rol `vendedor`: acceso restringido a sus propios leads asignados.
- 2FA opcional por usuario (TOTP compatible con Google Authenticator).

### ⚙️ Estados de Lead
- Estados por defecto: Nuevo Registro, Contactado, Descartado, Reunión, Negociación, Concretado.
- Configurables desde la interfaz: nombre, color (badge) e ícono.
- El orden determina la secuencia en el Kanban.

---

## Requisitos

### Desarrollo Local
- PHP **8.4** o superior con extensiones: `pdo`, `pdo_sqlite`/`pdo_pgsql`, `gd`, `mbstring`, `xml`
- Composer **2.x**
- Node.js **20** o superior
- SQLite (por defecto) o PostgreSQL 16

### Con Docker
- Docker **24+**
- Docker Compose **2.x**

---

## Instalación

### Opción 1 — Desarrollo Local (SQLite, recomendado para desarrollo)

```bash
# 1. Clonar repositorio
git clone <url-del-repositorio> crm-lite-20
cd crm-lite-20

# 2. Instalar dependencias PHP y Node
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Migraciones y datos iniciales
php artisan migrate --seed

# 5. Iniciar servidor de desarrollo (PHP + Queue + Logs + Vite en paralelo)
composer run dev
```

La aplicación estará disponible en `http://localhost:8000`.

> **Nota:** El comando `composer run dev` levanta simultáneamente el servidor PHP, el queue worker, el log viewer (Pail) y el dev server de Vite con hot-reload.

### Opción 2 — Con Docker (PostgreSQL, recomendado para staging/producción)

```bash
# 1. Clonar repositorio
git clone <url-del-repositorio> crm-lite-20
cd crm-lite-20

# 2. Verificar que el entorno Docker es compatible
./docker-verify.sh

# 3. Iniciar con el script automatizado
./docker-start.sh

# — O manualmente —
cp .env.docker .env
# Edita .env con tus credenciales de base de datos
docker-compose build
docker-compose up -d
```

La aplicación estará disponible en `http://localhost`.

### Instalación Completa con Make

```bash
make install   # Build + Up + Migrate en un solo comando
```

---

## Variables de Entorno

Copia `.env.example` y ajusta los valores según tu entorno:

```env
# ── Aplicación ──────────────────────────────────────
APP_NAME="CRM Lite"
APP_ENV=local            # local | production
APP_KEY=                 # Generada con: php artisan key:generate
APP_DEBUG=true           # false en producción
APP_URL=http://localhost

# ── Base de Datos ────────────────────────────────────
# SQLite (desarrollo local, por defecto)
DB_CONNECTION=sqlite

# PostgreSQL (Docker / producción)
# DB_CONNECTION=pgsql
# DB_HOST=db
# DB_PORT=5432
# DB_DATABASE=laravel
# DB_USERNAME=laravel
# DB_PASSWORD=secret

# ── Colas y Caché ────────────────────────────────────
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

# ── Email ─────────────────────────────────────────────
MAIL_MAILER=log          # Cambia a smtp en producción
MAIL_HOST=smtp.ejemplo.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="no-reply@tu-dominio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Docker

La imagen Docker está optimizada para producción con un build multi-stage:

| Etapa | Descripción |
|---|---|
| `builder` | Instala dependencias Composer y compila assets con Node |
| `production` | Imagen final PHP 8.4 FPM + Nginx + Supervisor |

### Servicios

| Servicio | Imagen | Puerto |
|---|---|---|
| `app` | Dockerfile (custom) | 80 |
| `db` | postgres:16-alpine | 5432 (interno) |

### Comandos Make

```bash
make help           # Listar todos los comandos disponibles
make install        # Instalación completa (build + up + migrate)
make up             # Levantar contenedores
make down           # Detener contenedores
make restart        # Reiniciar contenedores
make logs           # Ver logs de todos los servicios en tiempo real
make logs-app       # Ver logs solo de la aplicación
make logs-db        # Ver logs de PostgreSQL
make shell          # Acceder al shell del contenedor app
make artisan CMD="..." # Ejecutar comando Artisan (ej: make artisan CMD="route:list")
make migrate        # Ejecutar migraciones
make migrate-fresh  # Recrear base de datos y migrar
make seed           # Ejecutar seeders
make fresh          # migrate:fresh --seed
make cache-clear    # Limpiar todos los caches
make cache-optimize # Optimizar caches para producción
make backup-db      # Backup de PostgreSQL a archivo .sql
make restore-db FILE=backup.sql  # Restaurar backup
make backup-storage # Backup del volumen de archivos
make test           # Ejecutar suite de tests
make tinker         # Abrir Laravel Tinker
make ps             # Ver estado de contenedores
make stats          # Ver métricas de recursos (CPU/RAM)
make clean          # ⚠️ Elimina contenedores Y volúmenes (incluye DB)
```

### Volúmenes Persistentes

| Volumen | Contenido |
|---|---|
| `db-data` | Datos de PostgreSQL |
| `storage-data` | Archivos subidos por los usuarios |
| `./storage/logs` | Logs de la aplicación (bind mount) |

---

## Despliegue en Dokploy

Este proyecto incluye `dokploy.json` con configuración automática para Dokploy.

### Pasos

1. Crear un nuevo proyecto en [Dokploy](https://dokploy.com).
2. Conectar el repositorio Git.
3. Configurar las variables de entorno (usar los valores de producción de la sección anterior).
   - Asegura que `DB_PASSWORD` (app) y `POSTGRES_PASSWORD` (servicio `db`) sean el mismo valor.
   - Si ya existe volumen `db-data`, cambiar `POSTGRES_PASSWORD` no actualiza la contraseña interna automáticamente.
4. Hacer clic en **Deploy**.

Dokploy ejecutará automáticamente:
- Build de la imagen Docker (multi-stage).
- Creación del servicio PostgreSQL.
- Ejecución de migraciones.
- Configuración del dominio con SSL.
- Gestión de volúmenes persistentes.

---

## Scripts Disponibles

### Composer

```bash
composer run dev        # Servidor de desarrollo completo (PHP + Queue + Pail + Vite)
composer run dev:ssr    # Igual que dev pero con SSR de Inertia
composer run lint       # Formatear PHP con Laravel Pint
composer run test       # Lint + Tests con Pest
composer run setup      # Instalación completa desde cero (CI)
```

### NPM

```bash
npm run dev             # Vite dev server con hot-reload
npm run build           # Build de producción (assets en public/build/)
npm run build:ssr       # Build de producción + SSR
npm run format          # Formatear código Vue/TS/CSS con Prettier
npm run format:check    # Verificar formato sin modificar archivos
npm run lint            # ESLint con auto-fix
```

---

## Testing

El proyecto usa **Pest 4** para testing con integración en Laravel.

```bash
# Ejecutar todos los tests
php artisan test --compact

# Filtrar por nombre de test
php artisan test --compact --filter=LeadTest

# Con cobertura
php artisan test --coverage

# Con Docker
make test
# o
docker-compose exec app php artisan test
```

Los tests se encuentran en `tests/Feature/` y `tests/Unit/`.

---

## Seguridad

- **Autenticación**: Laravel Fortify con soporte para 2FA (TOTP).
- **Autorización**: RBAC basado en roles y permisos; los agentes no pueden ver ni modificar leads ajenos.
- **CSRF**: Protección automática en todas las solicitudes de formulario vía Inertia.
- **Validación**: Form Requests en todos los endpoints de escritura.
- **Contraseñas**: Hash con bcrypt (12 rounds).
- **Sesiones**: Almacenadas en base de datos con cifrado opcional.
- **Rate limiting**: Aplicado en rutas de autenticación.
- **Tokens de API**: Gestionados con Laravel Sanctum.


---

Desarrollado con ❤️ usando **Laravel 12**, **Vue 3** e **Inertia.js**.
