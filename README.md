# 🚀 Laravel CRM - Sistema de Gestión de Clientes

Sistema CRM moderno construido con Laravel 12, Vue.js 3, Inertia.js y TailwindCSS.

## ✨ Características

- 🔐 Autenticación completa con Laravel Fortify
- 👥 Gestión de usuarios y roles
- 🏢 Gestión de empresas
- 📊 Gestión de leads/prospectos
- 🎨 Interfaz moderna con Vue 3 + Inertia.js
- 🎨 Componentes UI con Radix Vue
- 📱 Diseño responsive con TailwindCSS 4
- 🔔 Notificaciones con SweetAlert2
- 🐳 Configuración Docker completa
- 📦 Listo para desplegar en Dokploy

## 🛠️ Stack Tecnológico

### Backend
- Laravel 12
- PHP 8.4+
- PostgreSQL 16
- Laravel Fortify (Autenticación)
- Laravel Sanctum (API)

### Frontend
- Vue.js 3
- Inertia.js
- TailwindCSS 4
- Radix Vue (Componentes UI)
- Lucide Icons
- SweetAlert2
- Vite

## 📋 Requisitos

### Desarrollo Local
- PHP 8.4 o superior
- Composer
- Node.js 20 o superior
- PostgreSQL 16 o SQLite

### Con Docker
- Docker
- Docker Compose

## 🚀 Instalación

### Opción 1: Desarrollo Local (Sin Docker)

```bash
# Clonar el repositorio
git clone <tu-repositorio>
cd crm-lite-20

# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# Luego ejecutar migraciones
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar servidor de desarrollo
composer run dev
```

### Opción 2: Con Docker (Recomendado)

```bash
# Clonar el repositorio
git clone <tu-repositorio>
cd crm-lite-20

# Verificar configuración
./docker-verify.sh

# Iniciar con Docker
./docker-start.sh

# O manualmente
cp .env.docker .env
# Edita .env con tus configuraciones
docker-compose build
docker-compose up -d
```

La aplicación estará disponible en `http://localhost`

## 🐳 Docker

Este proyecto incluye configuración completa de Docker optimizada para producción y Dokploy.

### Características de Docker

- ✅ Multi-stage build optimizado
- ✅ PHP 8.4 FPM + Nginx
- ✅ PostgreSQL 16
- ✅ Supervisor para gestión de procesos
- ✅ Queue workers automáticos
- ✅ Soporte para uploads hasta 100MB
- ✅ Volúmenes persistentes
- ✅ Health checks
- ✅ Configuración dinámica de dominio/puerto
- ✅ Build optimizado (Wayfinder se genera en runtime)

### Comandos Docker Útiles

```bash
# Ver todos los comandos disponibles
make help

# Instalación completa
make install

# Ver logs
make logs

# Ejecutar migraciones
make migrate

# Acceder al contenedor
make shell

# Backup de base de datos
make backup-db

# Detener contenedores
make down
```

Ver [README.Docker.md](README.Docker.md) para documentación completa de Docker.

## 🌐 Despliegue en Dokploy

Este proyecto está optimizado para despliegue en Dokploy con configuración automática.

### Pasos Rápidos

1. Crea un nuevo proyecto en Dokploy
2. Conecta tu repositorio Git
3. Configura las variables de entorno (ver DEPLOYMENT.md)
4. Haz clic en "Deploy"

Dokploy automáticamente:
- Construirá la imagen Docker
- Creará la base de datos PostgreSQL
- Ejecutará las migraciones
- Configurará el dominio
- Gestionará los volúmenes persistentes

Ver [DEPLOYMENT.md](DEPLOYMENT.md) para guía completa de despliegue.

## 📁 Estructura del Proyecto

```
.
├── app/                    # Código de la aplicación Laravel
│   ├── Http/Controllers/   # Controladores
│   ├── Models/            # Modelos Eloquent
│   └── Providers/         # Service Providers
├── resources/
│   ├── js/                # Código Vue.js
│   │   ├── components/    # Componentes Vue
│   │   ├── layouts/       # Layouts de Inertia
│   │   └── pages/         # Páginas de Inertia
│   └── css/               # Estilos
├── docker/                # Configuración Docker
│   ├── nginx/            # Configuración Nginx
│   ├── php/              # Configuración PHP
│   └── supervisor/       # Configuración Supervisor
├── database/
│   ├── migrations/       # Migraciones
│   └── seeders/          # Seeders
├── Dockerfile            # Imagen Docker
├── docker-compose.yml    # Orquestación Docker
└── Makefile             # Comandos útiles
```

## 🔧 Configuración

### Variables de Entorno Importantes

```env
# Aplicación
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Base de Datos
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=tu_password_segura
```

### Configuración de Uploads

El proyecto soporta uploads de archivos hasta 100MB. Los archivos se almacenan en:
- Local: `storage/app/public`
- Docker: Volumen persistente `storage-data`

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test

# Con Docker
docker-compose exec app php artisan test
# O
make test
```

## 📝 Scripts Disponibles

### Composer Scripts

```bash
composer run dev        # Servidor de desarrollo con hot reload
composer run build      # Compilar assets para producción
composer run lint       # Ejecutar PHP Pint
composer run test       # Ejecutar tests
```

### NPM Scripts

```bash
npm run dev            # Vite dev server
npm run build          # Build de producción
npm run format         # Formatear código con Prettier
npm run lint           # Linter ESLint
```

### Make Commands (Docker)

```bash
make help              # Ver todos los comandos
make install           # Instalación completa
make up                # Levantar contenedores
make down              # Detener contenedores
make logs              # Ver logs
make shell             # Acceder al contenedor
make migrate           # Ejecutar migraciones
make seed              # Ejecutar seeders
make backup-db         # Backup de base de datos
```

## 🔐 Seguridad

- Autenticación con Laravel Fortify
- Protección CSRF
- Validación de entrada
- Sanitización de datos
- Rate limiting
- Passwords hasheados con bcrypt
- Sesiones seguras

## 📚 Documentación Adicional

- [README.Docker.md](README.Docker.md) - Documentación completa de Docker
- [DEPLOYMENT.md](DEPLOYMENT.md) - Guía de despliegue en Dokploy
- [DOCKER_BUILD_NOTES.md](DOCKER_BUILD_NOTES.md) - Notas técnicas del build
- [INICIO_RAPIDO.md](INICIO_RAPIDO.md) - Guía de inicio rápido
- [ARQUITECTURA.md](ARQUITECTURA.md) - Arquitectura del sistema
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Documentación de API
- [CRM_README.md](CRM_README.md) - Funcionalidades del CRM

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

## 🆘 Soporte

Para problemas o preguntas:

1. Revisa la documentación en los archivos README
2. Verifica los logs: `make logs` (Docker) o `php artisan pail`
3. Ejecuta el verificador: `./docker-verify.sh`

## 🎯 Roadmap

- [ ] API REST completa
- [ ] Integración con servicios de email
- [ ] Dashboard con métricas
- [ ] Exportación de datos
- [ ] Notificaciones en tiempo real
- [ ] Aplicación móvil

---

Hecho con ❤️ usando Laravel, Vue.js e Inertia.js
