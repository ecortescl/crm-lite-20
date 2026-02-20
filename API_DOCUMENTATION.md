# Documentación de la API del CRM

## Introducción

Esta API permite gestionar empresas y leads de forma programática. Utiliza autenticación mediante tokens de Laravel Sanctum.

## Autenticación

### Generar un Token de API

1. Inicia sesión en el CRM
2. Ve a **Settings > API Tokens**
3. Haz clic en "Crear Nuevo Token"
4. Ingresa un nombre descriptivo para el token
5. Copia el token generado (solo se mostrará una vez)

### Usar el Token

Incluye el token en el header `Authorization` de todas tus peticiones:

```bash
Authorization: Bearer TU_TOKEN_AQUI
```

## URL Base

```
https://tu-dominio.com/api
```

## Documentación Swagger

Accede a la documentación interactiva completa en:

```
https://tu-dominio.com/api/documentation
```

## Endpoints Principales

### Empresas (Companies)

#### Listar Empresas
```http
GET /api/companies
```

**Parámetros de consulta:**
- `page` (opcional): Número de página
- `per_page` (opcional): Registros por página (default: 15)
- `search` (opcional): Búsqueda por nombre o RUT

**Ejemplo:**
```bash
curl -X GET "https://tu-dominio.com/api/companies?page=1&per_page=10" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Accept: application/json"
```

#### Crear Empresa
```http
POST /api/companies
```

**Body (JSON):**
```json
{
  "business_name": "Empresa Demo SpA",
  "rut": "76123456-7",
  "fantasy_name": "Demo",
  "giro": "Servicios de tecnología",
  "email": "contacto@demo.cl",
  "phone": "+56912345678",
  "website": "https://demo.cl",
  "address": "Av. Principal 123",
  "commune": "Santiago",
  "city": "Santiago",
  "region": "Metropolitana",
  "notes": "Cliente importante",
  "size": "medium",
  "industry": "Tecnología"
}
```

**Campos requeridos:**
- `business_name`: Razón social
- `rut`: RUT de la empresa (debe ser único)

**Ejemplo:**
```bash
curl -X POST "https://tu-dominio.com/api/companies" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "business_name": "Empresa Demo SpA",
    "rut": "76123456-7",
    "fantasy_name": "Demo",
    "email": "contacto@demo.cl"
  }'
```

#### Obtener Empresa
```http
GET /api/companies/{id}
```

#### Actualizar Empresa
```http
PUT /api/companies/{id}
```

#### Eliminar Empresa
```http
DELETE /api/companies/{id}
```

---

### Leads

#### Listar Leads
```http
GET /api/leads
```

**Parámetros de consulta:**
- `page` (opcional): Número de página
- `per_page` (opcional): Registros por página (default: 15)
- `search` (opcional): Búsqueda por nombre, email o teléfono
- `status` (opcional): Filtrar por ID de estado

**Ejemplo:**
```bash
curl -X GET "https://tu-dominio.com/api/leads?status=1" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Accept: application/json"
```

#### Crear Lead
```http
POST /api/leads
```

**Body (JSON):**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "phone": "+56912345678",
  "contact_company": "Empresa ABC",
  "company_id": 1,
  "notes": "Cliente interesado en producto X",
  "lead_status_id": 1,
  "assigned_to": 1,
  "source": "Website",
  "utm_source": "google",
  "utm_medium": "cpc",
  "utm_campaign": "summer_sale",
  "budget": 5000.00,
  "scheduled_at": "2024-12-25 10:00:00",
  "meeting_notes": "Reunión para presentar propuesta"
}
```

**Campos requeridos:**
- `name`: Nombre del lead
- `lead_status_id`: ID del estado del lead

**Nota:** Si no se especifica `assigned_to`, el lead se asignará automáticamente al usuario autenticado (dueño del token).

**Ejemplo:**
```bash
curl -X POST "https://tu-dominio.com/api/leads" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "phone": "+56912345678",
    "lead_status_id": 1,
    "source": "Website"
  }'
```

#### Obtener Lead
```http
GET /api/leads/{id}
```

#### Actualizar Lead
```http
PUT /api/leads/{id}
```

#### Eliminar Lead
```http
DELETE /api/leads/{id}
```

#### Listar Estados de Leads
```http
GET /api/lead-statuses
```

**Ejemplo:**
```bash
curl -X GET "https://tu-dominio.com/api/lead-statuses" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Accept: application/json"
```

---

## Respuestas de la API

### Respuesta Exitosa (200/201)

```json
{
  "message": "Lead creado exitosamente",
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    ...
  }
}
```

### Respuesta de Listado (200)

```json
{
  "data": [...],
  "current_page": 1,
  "last_page": 5,
  "per_page": 15,
  "total": 73
}
```

### Error de Validación (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

### Error de Autenticación (401)

```json
{
  "message": "Unauthenticated."
}
```

### Recurso No Encontrado (404)

```json
{
  "message": "No query results for model [App\\Models\\Lead] 999"
}
```

---

## Códigos de Estado HTTP

- `200 OK`: Petición exitosa
- `201 Created`: Recurso creado exitosamente
- `401 Unauthorized`: Token inválido o no proporcionado
- `404 Not Found`: Recurso no encontrado
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

---

## Ejemplos Completos

### Crear una Empresa y un Lead Asociado

```bash
# 1. Crear la empresa
COMPANY_RESPONSE=$(curl -X POST "https://tu-dominio.com/api/companies" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "business_name": "Tech Solutions SpA",
    "rut": "76555666-7",
    "email": "info@techsolutions.cl"
  }')

# Extraer el ID de la empresa (requiere jq)
COMPANY_ID=$(echo $COMPANY_RESPONSE | jq -r '.data.id')

# 2. Crear el lead asociado a la empresa
curl -X POST "https://tu-dominio.com/api/leads" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"name\": \"María González\",
    \"email\": \"maria@techsolutions.cl\",
    \"phone\": \"+56987654321\",
    \"company_id\": $COMPANY_ID,
    \"lead_status_id\": 1,
    \"source\": \"Referido\",
    \"notes\": \"Contacto de la empresa Tech Solutions\"
  }"
```

### Actualizar el Estado de un Lead

```bash
curl -X PUT "https://tu-dominio.com/api/leads/1" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "lead_status_id": 2,
    "notes": "Lead contactado, interesado en reunión"
  }'
```

---

## Límites y Consideraciones

- **Rate Limiting**: La API tiene límites de tasa para prevenir abuso
- **Paginación**: Por defecto se devuelven 15 registros por página
- **Tokens**: Los tokens no expiran automáticamente, pero pueden ser revocados desde Settings
- **Permisos**: Los tokens tienen acceso completo a los recursos del usuario autenticado

---

## Soporte

Para más información o soporte, contacta a: soporte@crm.com

## Changelog

### v1.0.0 (2024-02-19)
- Lanzamiento inicial de la API
- Endpoints para Empresas y Leads
- Autenticación con Sanctum
- Documentación Swagger
