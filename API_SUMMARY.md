# Resumen de Documentación de la API del CRM

## 📋 Archivos Creados

### 1. Controladores de API
- ✅ `app/Http/Controllers/Api/CalendarApiController.php` - Gestión de calendario y reuniones
- ✅ `app/Http/Controllers/Api/QuotationApiController.php` - Gestión de cotizaciones
- ✅ Actualizados: `CompanyApiController.php` y `LeadApiController.php` (ya existían)
- ✅ Actualizado: `SwaggerController.php` con schemas completos

### 2. Rutas
- ✅ `routes/api.php` - Rutas actualizadas con todos los endpoints

### 3. Documentación
- ✅ `API_DOCUMENTATION.md` - Documentación completa de la API
- ✅ `SWAGGER_SETUP.md` - Guía de instalación de Swagger
- ✅ `POSTMAN_GUIDE.md` - Guía de uso de Postman
- ✅ `postman_collection.json` - Colección completa de Postman

---

## 🚀 Endpoints Disponibles

### Empresas (Companies)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/companies` | Listar empresas |
| POST | `/api/companies` | Crear empresa |
| GET | `/api/companies/{id}` | Obtener empresa |
| PUT | `/api/companies/{id}` | Actualizar empresa |
| DELETE | `/api/companies/{id}` | Eliminar empresa |

### Leads
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/leads` | Listar leads |
| POST | `/api/leads` | Crear lead |
| GET | `/api/leads/{id}` | Obtener lead |
| PUT | `/api/leads/{id}` | Actualizar lead |
| DELETE | `/api/leads/{id}` | Eliminar lead |
| GET | `/api/lead-statuses` | Listar estados |

### Cotizaciones (Quotations)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/quotations` | Listar cotizaciones |
| POST | `/api/quotations` | Crear cotización |
| GET | `/api/quotations/{id}` | Obtener cotización |
| PUT | `/api/quotations/{id}` | Actualizar cotización |
| DELETE | `/api/quotations/{id}` | Eliminar cotización |
| PATCH | `/api/quotations/{id}/status` | Actualizar estado |
| GET | `/api/quotations/next-number` | Siguiente número |

### Calendario (Calendar)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/calendar/meetings` | Listar reuniones |
| POST | `/api/calendar/meetings` | Agendar reunión |
| DELETE | `/api/calendar/meetings/{lead_id}` | Cancelar reunión |

**Total: 21 endpoints documentados**

---

## 🔐 Autenticación

Todos los endpoints requieren autenticación mediante Bearer Token (Laravel Sanctum).

```http
Authorization: Bearer tu_token_aqui
```

### Generar Token
1. Inicia sesión en el CRM
2. Ve a Settings > API Tokens
3. Crea un nuevo token
4. Copia y usa en tus peticiones

---

## 📦 Características Implementadas

### Controladores de API
- ✅ Validación completa de datos
- ✅ Respuestas JSON estandarizadas
- ✅ Manejo de errores
- ✅ Paginación en listados
- ✅ Filtros y búsqueda
- ✅ Relaciones cargadas (eager loading)

### Documentación Swagger
- ✅ Anotaciones OpenAPI 3.0
- ✅ Schemas definidos para todos los modelos
- ✅ Ejemplos de request/response
- ✅ Descripción de parámetros
- ✅ Códigos de estado HTTP
- ✅ Autenticación Bearer Token

### Colección Postman
- ✅ 21 peticiones preconfiguradas
- ✅ Variables de entorno
- ✅ Ejemplos de datos
- ✅ Organización por módulos
- ✅ Documentación inline

---

## 🎯 Próximos Pasos

### 1. Instalar Swagger (Opcional)
```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

Accede a: `http://localhost:8000/api/documentation`

### 2. Importar Colección de Postman
1. Abre Postman
2. Import > `postman_collection.json`
3. Configura la variable `api_token`
4. ¡Listo para probar!

### 3. Probar los Endpoints

#### Ejemplo con cURL:
```bash
# Listar empresas
curl -H "Authorization: Bearer tu_token" \
     http://localhost:8000/api/companies

# Crear lead
curl -X POST \
     -H "Authorization: Bearer tu_token" \
     -H "Content-Type: application/json" \
     -d '{"name":"Juan Pérez","lead_status_id":1}' \
     http://localhost:8000/api/leads
```

---

## 📚 Documentación Disponible

### Para Desarrolladores
- **API_DOCUMENTATION.md** - Referencia completa de la API
  - Todos los endpoints
  - Parámetros y respuestas
  - Ejemplos de uso
  - Códigos de estado

### Para Configuración
- **SWAGGER_SETUP.md** - Instalación de Swagger
  - Pasos de instalación
  - Configuración
  - Troubleshooting

### Para Testing
- **POSTMAN_GUIDE.md** - Guía de Postman
  - Importar colección
  - Configurar variables
  - Flujos de trabajo
  - Tips y mejores prácticas

---

## 🔍 Validaciones Implementadas

### Empresas
- `business_name`: requerido, máx 255 caracteres
- `rut`: requerido, único, máx 255 caracteres
- `email`: formato email válido
- `website`: formato URL válido
- `size`: enum (small, medium, large, enterprise)

### Leads
- `name`: requerido, máx 255 caracteres
- `email`: formato email válido
- `lead_status_id`: requerido, debe existir
- `company_id`: debe existir si se proporciona
- `budget`: numérico, mínimo 0
- `payment_status`: enum (pending, partial, paid)

### Cotizaciones
- `quotation_number`: requerido, único
- `client_name`: requerido, máx 255 caracteres
- `issue_date`: requerido, formato fecha
- `valid_until`: requerido, debe ser posterior a issue_date
- `items`: array requerido, mínimo 1 item
- `tax_rate`: numérico, 0-100
- `status`: enum (draft, sent, accepted, rejected, expired)

### Calendario
- `lead_id`: requerido, debe existir
- `scheduled_at`: requerido, formato fecha-hora
- `meeting_link`: formato URL válido, máx 2048 caracteres

---

## 🎨 Schemas de Datos

### Company
```json
{
  "id": 1,
  "business_name": "Empresa Demo SpA",
  "rut": "76123456-7",
  "fantasy_name": "Demo",
  "email": "contacto@demo.cl",
  "phone": "+56912345678",
  "size": "medium",
  "industry": "Tecnología"
}
```

### Lead
```json
{
  "id": 1,
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "phone": "+56912345678",
  "lead_status_id": 1,
  "budget": 5000.00,
  "status": {
    "id": 1,
    "name": "Nuevo",
    "color": "#3b82f6"
  }
}
```

### Quotation
```json
{
  "id": 1,
  "quotation_number": "COT-2024-001",
  "client_name": "Juan Pérez",
  "issue_date": "2024-01-15",
  "valid_until": "2024-02-15",
  "subtotal": 500000.00,
  "tax_rate": 19.00,
  "tax_amount": 95000.00,
  "total": 595000.00,
  "status": "draft",
  "items": [...]
}
```

---

## ✅ Checklist de Implementación

- [x] Controlador de API para Calendario
- [x] Controlador de API para Cotizaciones
- [x] Actualización de rutas API
- [x] Anotaciones Swagger completas
- [x] Schemas de datos definidos
- [x] Colección de Postman
- [x] Documentación completa en Markdown
- [x] Guía de instalación de Swagger
- [x] Guía de uso de Postman
- [x] Ejemplos de uso
- [x] Validaciones implementadas

---

## 🤝 Soporte

Para más información o dudas:
- Revisa `API_DOCUMENTATION.md` para detalles de endpoints
- Consulta `POSTMAN_GUIDE.md` para testing
- Lee `SWAGGER_SETUP.md` para documentación interactiva

---

## 📝 Notas Importantes

1. **Autenticación**: Todos los endpoints requieren token de API
2. **Paginación**: Los listados soportan `page` y `per_page`
3. **Filtros**: Búsqueda disponible en empresas, leads y cotizaciones
4. **Relaciones**: Los datos incluyen relaciones cargadas automáticamente
5. **Validación**: Respuestas 422 incluyen detalles de errores de validación

---

## 🎉 ¡Listo para Usar!

La API está completamente documentada y lista para ser consumida. Puedes:

1. ✅ Usar la colección de Postman para testing rápido
2. ✅ Instalar Swagger para documentación interactiva
3. ✅ Consultar la documentación Markdown para referencia
4. ✅ Integrar con aplicaciones frontend o móviles
5. ✅ Crear integraciones con sistemas externos

**¡Feliz desarrollo! 🚀**
