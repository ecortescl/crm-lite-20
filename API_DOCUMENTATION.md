# Documentación de la API del CRM

## Índice
- [Introducción](#introducción)
- [Autenticación](#autenticación)
- [Endpoints](#endpoints)
  - [Empresas (Companies)](#empresas-companies)
  - [Leads](#leads)
  - [Cotizaciones (Quotations)](#cotizaciones-quotations)
  - [Calendario (Calendar)](#calendario-calendar)
- [Códigos de Estado](#códigos-de-estado)
- [Ejemplos de Uso](#ejemplos-de-uso)

---

## Introducción

Esta API REST permite gestionar empresas, leads, cotizaciones y reuniones en el sistema CRM. Todos los endpoints requieren autenticación mediante tokens de API (Laravel Sanctum).

**URL Base:** `http://localhost:8000/api`

**Versión:** 1.0.0

---

## Autenticación

La API utiliza Laravel Sanctum para autenticación mediante Bearer Tokens.

### Generar un Token de API

1. Inicia sesión en el sistema CRM
2. Ve a **Settings > API Tokens**
3. Crea un nuevo token y cópialo
4. Usa el token en el header de tus peticiones:

```http
Authorization: Bearer tu_token_aqui
```

### Ejemplo con cURL

```bash
curl -H "Authorization: Bearer tu_token_aqui" \
     -H "Content-Type: application/json" \
     http://localhost:8000/api/companies
```

---

## Endpoints

### Empresas (Companies)

#### Listar Empresas

```http
GET /api/companies
```

**Parámetros de Query:**
- `page` (opcional): Número de página (default: 1)
- `per_page` (opcional): Registros por página (default: 15)
- `search` (opcional): Búsqueda por nombre o RUT

**Respuesta exitosa (200):**
```json
{
  "data": [
    {
      "id": 1,
      "business_name": "Empresa Demo SpA",
      "rut": "76123456-7",
      "fantasy_name": "Demo",
      "email": "contacto@demo.cl",
      "phone": "+56912345678",
      "created_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "current_page": 1,
  "last_page": 5,
  "per_page": 15,
  "total": 75
}
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
- `rut`: RUT de la empresa (único)

**Respuesta exitosa (201):**
```json
{
  "message": "Empresa creada exitosamente",
  "data": {
    "id": 1,
    "business_name": "Empresa Demo SpA",
    "rut": "76123456-7",
    ...
  }
}
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

**Parámetros de Query:**
- `page` (opcional): Número de página
- `per_page` (opcional): Registros por página
- `search` (opcional): Búsqueda por nombre, email o teléfono
- `status` (opcional): Filtrar por ID de estado

**Respuesta exitosa (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "phone": "+56912345678",
      "lead_status_id": 1,
      "status": {
        "id": 1,
        "name": "Nuevo",
        "color": "#3b82f6"
      },
      "assigned_user": {
        "id": 1,
        "name": "Admin User"
      },
      "company": {
        "id": 1,
        "business_name": "Empresa ABC"
      }
    }
  ]
}
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

**Respuesta exitosa (201):**
```json
{
  "message": "Lead creado exitosamente",
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    ...
  }
}
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

**Respuesta exitosa (200):**
```json
[
  {
    "id": 1,
    "name": "Nuevo",
    "color": "#3b82f6",
    "order": 1
  },
  {
    "id": 2,
    "name": "Contactado",
    "color": "#10b981",
    "order": 2
  }
]
```

---

### Cotizaciones (Quotations)

#### Listar Cotizaciones

```http
GET /api/quotations
```

**Parámetros de Query:**
- `page` (opcional): Número de página
- `per_page` (opcional): Registros por página
- `search` (opcional): Búsqueda por número, nombre o RUT del cliente
- `status` (opcional): Filtrar por estado (draft, sent, accepted, rejected, expired)

**Respuesta exitosa (200):**
```json
{
  "data": [
    {
      "id": 1,
      "quotation_number": "COT-2024-001",
      "client_name": "Juan Pérez",
      "client_email": "juan@example.com",
      "issue_date": "2024-01-15",
      "valid_until": "2024-02-15",
      "subtotal": 500000.00,
      "tax_rate": 19.00,
      "tax_amount": 95000.00,
      "total": 595000.00,
      "status": "draft",
      "items": [
        {
          "description": "Servicio de consultoría",
          "quantity": 10,
          "unit_price": 50000,
          "subtotal": 500000
        }
      ]
    }
  ]
}
```

#### Crear Cotización

```http
POST /api/quotations
```

**Body (JSON):**
```json
{
  "quotation_number": "COT-2024-001",
  "lead_id": 1,
  "company_id": 1,
  "client_name": "Juan Pérez",
  "client_rut": "12345678-9",
  "client_email": "juan@example.com",
  "client_phone": "+56912345678",
  "client_address": "Av. Principal 123",
  "issue_date": "2024-01-15",
  "valid_until": "2024-02-15",
  "items": [
    {
      "description": "Servicio de consultoría",
      "quantity": 10,
      "unit_price": 50000,
      "subtotal": 500000
    }
  ],
  "tax_rate": 19,
  "notes": "Notas adicionales",
  "terms": "Pago 50% al inicio, 50% al finalizar"
}
```

**Campos requeridos:**
- `quotation_number`: Número único de cotización
- `client_name`: Nombre del cliente
- `issue_date`: Fecha de emisión
- `valid_until`: Fecha de vencimiento (debe ser posterior a issue_date)
- `items`: Array de items (mínimo 1)
  - `description`: Descripción del item
  - `quantity`: Cantidad
  - `unit_price`: Precio unitario
  - `subtotal`: Subtotal del item
- `tax_rate`: Tasa de impuesto (%)

**Respuesta exitosa (201):**
```json
{
  "message": "Cotización creada exitosamente",
  "data": {
    "id": 1,
    "quotation_number": "COT-2024-001",
    "total": 595000.00,
    ...
  }
}
```

#### Obtener Cotización

```http
GET /api/quotations/{id}
```

#### Actualizar Cotización

```http
PUT /api/quotations/{id}
```

#### Eliminar Cotización

```http
DELETE /api/quotations/{id}
```

#### Actualizar Estado de Cotización

```http
PATCH /api/quotations/{id}/status
```

**Body (JSON):**
```json
{
  "status": "sent"
}
```

**Estados válidos:**
- `draft`: Borrador
- `sent`: Enviada
- `accepted`: Aceptada
- `rejected`: Rechazada
- `expired`: Expirada

#### Obtener Siguiente Número de Cotización

```http
GET /api/quotations/next-number
```

**Respuesta exitosa (200):**
```json
{
  "quotation_number": "COT-2024-001"
}
```

---

### Calendario (Calendar)

#### Listar Reuniones

```http
GET /api/calendar/meetings
```

**Parámetros de Query:**
- `start_date` (opcional): Fecha de inicio (formato: Y-m-d)
- `end_date` (opcional): Fecha de fin (formato: Y-m-d)

**Respuesta exitosa (200):**
```json
[
  {
    "id": 1,
    "name": "Juan Pérez",
    "scheduled_at": "2024-12-25T10:00:00.000000Z",
    "meeting_notes": "Reunión para presentar propuesta",
    "meeting_link": "https://meet.google.com/abc-defg-hij",
    "status": {
      "id": 3,
      "name": "Reunión Agendada"
    },
    "assigned_user": {
      "id": 1,
      "name": "Admin User"
    }
  }
]
```

#### Agendar Reunión

```http
POST /api/calendar/meetings
```

**Body (JSON):**
```json
{
  "lead_id": 1,
  "scheduled_at": "2024-12-25 10:00:00",
  "meeting_notes": "Reunión para presentar propuesta comercial",
  "meeting_link": "https://meet.google.com/abc-defg-hij"
}
```

**Campos requeridos:**
- `lead_id`: ID del lead
- `scheduled_at`: Fecha y hora de la reunión

**Respuesta exitosa (200):**
```json
{
  "message": "Reunión agendada exitosamente",
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "scheduled_at": "2024-12-25T10:00:00.000000Z",
    ...
  }
}
```

#### Cancelar Reunión

```http
DELETE /api/calendar/meetings/{lead_id}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Reunión cancelada exitosamente"
}
```

---

## Códigos de Estado

| Código | Descripción |
|--------|-------------|
| 200 | OK - Petición exitosa |
| 201 | Created - Recurso creado exitosamente |
| 204 | No Content - Petición exitosa sin contenido |
| 400 | Bad Request - Petición inválida |
| 401 | Unauthorized - No autenticado |
| 403 | Forbidden - Sin permisos |
| 404 | Not Found - Recurso no encontrado |
| 422 | Unprocessable Entity - Error de validación |
| 500 | Internal Server Error - Error del servidor |

---

## Ejemplos de Uso

### Ejemplo 1: Crear una empresa y un lead asociado

```bash
# 1. Crear empresa
curl -X POST http://localhost:8000/api/companies \
  -H "Authorization: Bearer tu_token" \
  -H "Content-Type: application/json" \
  -d '{
    "business_name": "Tech Solutions SpA",
    "rut": "76555666-7",
    "email": "info@techsolutions.cl",
    "phone": "+56912345678"
  }'

# 2. Crear lead asociado a la empresa (usar el ID retornado)
curl -X POST http://localhost:8000/api/leads \
  -H "Authorization: Bearer tu_token" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "María González",
    "email": "maria@techsolutions.cl",
    "phone": "+56987654321",
    "company_id": 1,
    "lead_status_id": 1,
    "source": "Website"
  }'
```

### Ejemplo 2: Crear cotización para un lead

```bash
# 1. Obtener siguiente número de cotización
curl -X GET http://localhost:8000/api/quotations/next-number \
  -H "Authorization: Bearer tu_token"

# 2. Crear cotización
curl -X POST http://localhost:8000/api/quotations \
  -H "Authorization: Bearer tu_token" \
  -H "Content-Type: application/json" \
  -d '{
    "quotation_number": "COT-2024-001",
    "lead_id": 1,
    "client_name": "María González",
    "client_email": "maria@techsolutions.cl",
    "issue_date": "2024-01-15",
    "valid_until": "2024-02-15",
    "items": [
      {
        "description": "Desarrollo de sitio web",
        "quantity": 1,
        "unit_price": 1500000,
        "subtotal": 1500000
      }
    ],
    "tax_rate": 19
  }'

# 3. Actualizar estado a "enviada"
curl -X PATCH http://localhost:8000/api/quotations/1/status \
  -H "Authorization: Bearer tu_token" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "sent"
  }'
```

### Ejemplo 3: Agendar reunión con un lead

```bash
curl -X POST http://localhost:8000/api/calendar/meetings \
  -H "Authorization: Bearer tu_token" \
  -H "Content-Type: application/json" \
  -d '{
    "lead_id": 1,
    "scheduled_at": "2024-12-25 10:00:00",
    "meeting_notes": "Presentación de propuesta comercial",
    "meeting_link": "https://meet.google.com/abc-defg-hij"
  }'
```

---

## Documentación Swagger

Para acceder a la documentación interactiva de Swagger:

1. Genera la documentación:
```bash
php artisan l5-swagger:generate
```

2. Accede a: `http://localhost:8000/api/documentation`

---

## Colección de Postman

Importa el archivo `postman_collection.json` en Postman para tener todos los endpoints preconfigurados.

**Pasos:**
1. Abre Postman
2. Click en "Import"
3. Selecciona el archivo `postman_collection.json`
4. Configura la variable `api_token` con tu token de API
5. ¡Listo para usar!

---

## Soporte

Para más información o soporte, contacta a: soporte@crm.com
