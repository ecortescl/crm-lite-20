# Guía de Inicio Rápido - API del CRM

## 🚀 Prueba Rápida en 5 Minutos

### Paso 1: Generar Token de API

```bash
# Opción A: Desde la aplicación web
# 1. Inicia sesión en http://localhost:8000
# 2. Ve a Settings > API Tokens
# 3. Crea un nuevo token y cópialo

# Opción B: Desde la terminal (desarrollo)
php artisan tinker
```

En tinker:
```php
$user = App\Models\User::first();
$token = $user->createToken('test-api')->plainTextToken;
echo $token;
exit
```

Copia el token generado.

### Paso 2: Probar Endpoints

#### 1. Listar Empresas

```bash
curl -H "Authorization: Bearer TU_TOKEN_AQUI" \
     http://localhost:8000/api/companies
```

#### 2. Crear una Empresa

```bash
curl -X POST http://localhost:8000/api/companies \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "business_name": "Mi Empresa Test",
    "rut": "76999888-7",
    "email": "test@empresa.cl",
    "phone": "+56912345678"
  }'
```

#### 3. Listar Leads

```bash
curl -H "Authorization: Bearer TU_TOKEN_AQUI" \
     http://localhost:8000/api/leads
```

#### 4. Crear un Lead

```bash
curl -X POST http://localhost:8000/api/leads \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@test.cl",
    "phone": "+56987654321",
    "lead_status_id": 1,
    "source": "API Test"
  }'
```

#### 5. Obtener Siguiente Número de Cotización

```bash
curl -H "Authorization: Bearer TU_TOKEN_AQUI" \
     http://localhost:8000/api/quotations/next-number
```

#### 6. Crear una Cotización

```bash
curl -X POST http://localhost:8000/api/quotations \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "quotation_number": "COT-2024-TEST",
    "client_name": "Cliente Test",
    "client_email": "cliente@test.cl",
    "issue_date": "2024-02-25",
    "valid_until": "2024-03-25",
    "items": [
      {
        "description": "Servicio de prueba",
        "quantity": 1,
        "unit_price": 100000,
        "subtotal": 100000
      }
    ],
    "tax_rate": 19
  }'
```

#### 7. Listar Reuniones

```bash
curl -H "Authorization: Bearer TU_TOKEN_AQUI" \
     http://localhost:8000/api/calendar/meetings
```

---

## 📦 Usar Postman (Recomendado)

### Importar Colección

1. Abre Postman
2. Click en **Import**
3. Selecciona `postman_collection.json`
4. Configura la variable `api_token` con tu token

### Probar Endpoints

1. Abre la carpeta "Companies"
2. Selecciona "Listar Empresas"
3. Click en **Send**
4. ¡Deberías ver la lista de empresas!

---

## 🔍 Verificar Rutas

```bash
# Ver todas las rutas de la API
php artisan route:list --path=api

# Deberías ver 21 rutas:
# - 5 para Companies
# - 6 para Leads
# - 7 para Quotations
# - 3 para Calendar
```

---

## ✅ Checklist de Verificación

- [ ] Token de API generado
- [ ] Endpoint de empresas funciona (GET /api/companies)
- [ ] Endpoint de leads funciona (GET /api/leads)
- [ ] Endpoint de cotizaciones funciona (GET /api/quotations)
- [ ] Endpoint de calendario funciona (GET /api/calendar/meetings)
- [ ] Puedo crear una empresa (POST /api/companies)
- [ ] Puedo crear un lead (POST /api/leads)
- [ ] Puedo crear una cotización (POST /api/quotations)

---

## 🐛 Troubleshooting Rápido

### Error 401: Unauthorized
```bash
# Verifica que el token sea correcto
# Genera un nuevo token si es necesario
```

### Error 404: Not Found
```bash
# Verifica que el servidor esté corriendo
php artisan serve

# Verifica la URL base
# Debe ser: http://localhost:8000/api
```

### Error 422: Validation Error
```bash
# Revisa los campos requeridos en la documentación
# Ejemplo: lead_status_id es requerido para crear leads
```

### Error de Conexión
```bash
# Asegúrate de que el servidor esté corriendo
php artisan serve

# Verifica que la base de datos esté configurada
php artisan migrate
```

---

## 📚 Siguiente Paso

Una vez que hayas probado los endpoints básicos:

1. **Lee la documentación completa**: `API_DOCUMENTATION.md`
2. **Explora Postman**: `POSTMAN_GUIDE.md`
3. **Instala Swagger** (opcional): `SWAGGER_SETUP.md`

---

## 🎯 Endpoints Más Usados

### Empresas
- `GET /api/companies` - Listar
- `POST /api/companies` - Crear
- `GET /api/companies/{id}` - Ver detalles

### Leads
- `GET /api/leads` - Listar
- `POST /api/leads` - Crear
- `PUT /api/leads/{id}` - Actualizar
- `GET /api/lead-statuses` - Estados disponibles

### Cotizaciones
- `GET /api/quotations` - Listar
- `GET /api/quotations/next-number` - Siguiente número
- `POST /api/quotations` - Crear
- `PATCH /api/quotations/{id}/status` - Cambiar estado

### Calendario
- `GET /api/calendar/meetings` - Listar reuniones
- `POST /api/calendar/meetings` - Agendar reunión

---

## 💡 Tips Rápidos

1. **Usa variables en cURL**:
```bash
export API_TOKEN="tu_token_aqui"
export API_URL="http://localhost:8000/api"

curl -H "Authorization: Bearer $API_TOKEN" $API_URL/companies
```

2. **Guarda respuestas**:
```bash
curl -H "Authorization: Bearer $API_TOKEN" \
     $API_URL/companies > companies.json
```

3. **Formato JSON legible**:
```bash
curl -H "Authorization: Bearer $API_TOKEN" \
     $API_URL/companies | jq
```

---

## 🎉 ¡Listo!

Ahora tienes la API completamente funcional y documentada. 

**Próximos pasos sugeridos:**
1. Integrar con tu frontend
2. Crear webhooks para eventos
3. Implementar rate limiting
4. Agregar más filtros y búsquedas
5. Crear tests automatizados

**¡Feliz desarrollo! 🚀**
