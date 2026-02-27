# 🚀 API del CRM - Documentación Completa

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![API](https://img.shields.io/badge/API-REST-blue.svg)](https://restfulapi.net/)
[![Auth](https://img.shields.io/badge/Auth-Sanctum-green.svg)](https://laravel.com/docs/sanctum)

API REST completa para gestión de CRM con endpoints para empresas, leads, cotizaciones y calendario.

---

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Inicio Rápido](#-inicio-rápido)
- [Documentación](#-documentación)
- [Endpoints](#-endpoints)
- [Autenticación](#-autenticación)
- [Ejemplos](#-ejemplos)
- [Herramientas](#-herramientas)
- [Soporte](#-soporte)

---

## ✨ Características

- ✅ **21 endpoints** completamente documentados
- ✅ **4 módulos**: Empresas, Leads, Cotizaciones, Calendario
- ✅ **Autenticación** con Laravel Sanctum
- ✅ **Validación** completa de datos
- ✅ **Paginación** en listados
- ✅ **Filtros y búsqueda** avanzada
- ✅ **Colección de Postman** incluida
- ✅ **Anotaciones Swagger/OpenAPI** 3.0
- ✅ **Documentación** en Markdown

---

## 🚀 Inicio Rápido

### 1. Generar Token de API

```bash
# Desde la aplicación web
# 1. Inicia sesión en http://localhost:8000
# 2. Ve a Settings > API Tokens
# 3. Crea un nuevo token

# O desde la terminal (desarrollo)
php artisan tinker
```

```php
$user = App\Models\User::first();
$token = $user->createToken('test')->plainTextToken;
echo $token;
```

### 2. Hacer tu Primera Petición

```bash
curl -H "Authorization: Bearer TU_TOKEN" \
     http://localhost:8000/api/companies
```

### 3. Explorar con Postman

1. Importa `postman_collection.json` en Postman
2. Configura la variable `api_token`
3. ¡Empieza a probar!

**[Ver guía completa de inicio rápido →](./QUICK_START.md)**

---

## 📚 Documentación

### Documentos Disponibles

| Documento | Descripción | Para Quién |
|-----------|-------------|------------|
| **[API_INDEX.md](./API_INDEX.md)** | Índice de toda la documentación | Todos |
| **[QUICK_START.md](./QUICK_START.md)** | Inicio rápido en 5 minutos | Principiantes |
| **[API_DOCUMENTATION.md](./API_DOCUMENTATION.md)** | Referencia completa de la API | Desarrolladores |
| **[API_SUMMARY.md](./API_SUMMARY.md)** | Resumen ejecutivo | Gerentes/Overview |
| **[POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md)** | Guía de uso de Postman | Testers |
| **[SWAGGER_SETUP.md](./SWAGGER_SETUP.md)** | Instalación de Swagger | DevOps |

### Navegación Rápida

- 🎯 **¿Primera vez?** → [QUICK_START.md](./QUICK_START.md)
- 📖 **¿Necesitas referencia?** → [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- 🧪 **¿Quieres testear?** → [POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md)
- 📊 **¿Necesitas overview?** → [API_SUMMARY.md](./API_SUMMARY.md)

---

## 🎯 Endpoints

### Resumen

| Módulo | Endpoints | Descripción |
|--------|-----------|-------------|
| **Companies** | 5 | Gestión de empresas |
| **Leads** | 6 | Gestión de leads y estados |
| **Quotations** | 7 | Gestión de cotizaciones |
| **Calendar** | 3 | Gestión de reuniones |
| **Total** | **21** | |

### Empresas (Companies)

```http
GET    /api/companies           # Listar empresas
POST   /api/companies           # Crear empresa
GET    /api/companies/{id}      # Obtener empresa
PUT    /api/companies/{id}      # Actualizar empresa
DELETE /api/companies/{id}      # Eliminar empresa
```

### Leads

```http
GET    /api/leads               # Listar leads
POST   /api/leads               # Crear lead
GET    /api/leads/{id}          # Obtener lead
PUT    /api/leads/{id}          # Actualizar lead
DELETE /api/leads/{id}          # Eliminar lead
GET    /api/lead-statuses       # Listar estados
```

### Cotizaciones (Quotations)

```http
GET    /api/quotations                  # Listar cotizaciones
POST   /api/quotations                  # Crear cotización
GET    /api/quotations/next-number      # Siguiente número
GET    /api/quotations/{id}             # Obtener cotización
PUT    /api/quotations/{id}             # Actualizar cotización
DELETE /api/quotations/{id}             # Eliminar cotización
PATCH  /api/quotations/{id}/status      # Actualizar estado
```

### Calendario (Calendar)

```http
GET    /api/calendar/meetings           # Listar reuniones
POST   /api/calendar/meetings           # Agendar reunión
DELETE /api/calendar/meetings/{lead_id} # Cancelar reunión
```

**[Ver documentación completa de endpoints →](./API_DOCUMENTATION.md)**

---

## 🔐 Autenticación

La API utiliza **Laravel Sanctum** para autenticación mediante Bearer Tokens.

### Generar Token

**Desde la aplicación:**
1. Settings > API Tokens
2. Create New Token
3. Copiar token

**Desde código:**
```php
$token = $user->createToken('token-name')->plainTextToken;
```

### Usar Token

```http
Authorization: Bearer tu_token_aqui
```

### Ejemplo con cURL

```bash
curl -H "Authorization: Bearer 1|abc123..." \
     -H "Content-Type: application/json" \
     http://localhost:8000/api/companies
```

**[Más información sobre autenticación →](./API_DOCUMENTATION.md#autenticación)**

---

## 💡 Ejemplos

### Crear una Empresa

```bash
curl -X POST http://localhost:8000/api/companies \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "business_name": "Tech Solutions SpA",
    "rut": "76555666-7",
    "email": "info@techsolutions.cl",
    "phone": "+56912345678"
  }'
```

### Crear un Lead

```bash
curl -X POST http://localhost:8000/api/leads \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "María González",
    "email": "maria@example.com",
    "lead_status_id": 1,
    "source": "Website"
  }'
```

### Crear una Cotización

```bash
curl -X POST http://localhost:8000/api/quotations \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "quotation_number": "COT-2024-001",
    "client_name": "María González",
    "issue_date": "2024-02-25",
    "valid_until": "2024-03-25",
    "items": [{
      "description": "Desarrollo web",
      "quantity": 1,
      "unit_price": 1500000,
      "subtotal": 1500000
    }],
    "tax_rate": 19
  }'
```

### Agendar Reunión

```bash
curl -X POST http://localhost:8000/api/calendar/meetings \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "lead_id": 1,
    "scheduled_at": "2024-12-25 10:00:00",
    "meeting_notes": "Presentación de propuesta"
  }'
```

**[Ver más ejemplos →](./QUICK_START.md)**

---

## 🛠️ Herramientas

### Postman

Colección completa con 21 peticiones preconfiguradas.

**Importar:**
1. Abre Postman
2. Import > `postman_collection.json`
3. Configura `api_token`

**[Ver guía completa de Postman →](./POSTMAN_GUIDE.md)**

### Swagger / OpenAPI

Documentación interactiva con Swagger UI.

**Instalar:**
```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

**Acceder:**
```
http://localhost:8000/api/documentation
```

**[Ver guía de instalación →](./SWAGGER_SETUP.md)**

---

## 📊 Respuestas de la API

### Respuesta Exitosa (200)

```json
{
  "data": [
    {
      "id": 1,
      "business_name": "Empresa Demo SpA",
      "rut": "76123456-7",
      "email": "contacto@demo.cl",
      "created_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "current_page": 1,
  "last_page": 5,
  "per_page": 15,
  "total": 75
}
```

### Respuesta de Creación (201)

```json
{
  "message": "Empresa creada exitosamente",
  "data": {
    "id": 1,
    "business_name": "Empresa Demo SpA",
    ...
  }
}
```

### Error de Validación (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "business_name": [
      "The business name field is required."
    ],
    "rut": [
      "The rut has already been taken."
    ]
  }
}
```

---

## 🔍 Filtros y Búsqueda

### Paginación

```http
GET /api/companies?page=2&per_page=20
```

### Búsqueda

```http
GET /api/companies?search=tech
GET /api/leads?search=juan&status=1
```

### Filtros por Fecha

```http
GET /api/calendar/meetings?start_date=2024-01-01&end_date=2024-12-31
```

**[Ver más sobre filtros →](./API_DOCUMENTATION.md)**

---

## 🧪 Testing

### Verificar Rutas

```bash
php artisan route:list --path=api
```

### Test con cURL

```bash
# Guardar token en variable
export API_TOKEN="tu_token_aqui"

# Hacer peticiones
curl -H "Authorization: Bearer $API_TOKEN" \
     http://localhost:8000/api/companies
```

### Test con Postman

1. Importa la colección
2. Configura variables
3. Ejecuta las peticiones
4. Verifica respuestas

**[Ver guía de testing →](./POSTMAN_GUIDE.md)**

---

## 📦 Instalación

### Requisitos

- PHP 8.2+
- Laravel 12.x
- Composer
- Base de datos (MySQL/PostgreSQL/SQLite)

### Setup

```bash
# Instalar dependencias
composer install

# Configurar .env
cp .env.example .env
php artisan key:generate

# Migrar base de datos
php artisan migrate

# Iniciar servidor
php artisan serve
```

---

## 🐛 Troubleshooting

### Error 401: Unauthorized
- Verifica que el token sea válido
- Asegúrate de incluir el header Authorization

### Error 422: Validation Error
- Revisa los campos requeridos
- Verifica los tipos de datos

### Error 404: Not Found
- Confirma que el servidor esté corriendo
- Verifica la URL base

**[Ver más soluciones →](./QUICK_START.md#troubleshooting-rápido)**

---

## 📞 Soporte

### Documentación
- [Índice completo](./API_INDEX.md)
- [Referencia de API](./API_DOCUMENTATION.md)
- [Guía de Postman](./POSTMAN_GUIDE.md)

### Contacto
- Email: soporte@crm.com
- Documentación Swagger: http://localhost:8000/api/documentation

---

## 📝 Licencia

Este proyecto está bajo la licencia MIT.

---

## 🎉 ¡Comienza Ahora!

1. **[Inicio Rápido](./QUICK_START.md)** - Prueba en 5 minutos
2. **[Documentación](./API_DOCUMENTATION.md)** - Referencia completa
3. **[Postman](./POSTMAN_GUIDE.md)** - Testing con Postman

**¡Feliz desarrollo! 🚀**

---

**Última actualización**: 25 de Febrero, 2026  
**Versión**: 1.0.0  
**Endpoints**: 21  
**Módulos**: 4
