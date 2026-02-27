# Guía de Uso de la Colección de Postman

## Importar la Colección

1. Abre Postman
2. Haz click en **Import** (esquina superior izquierda)
3. Selecciona el archivo `postman_collection.json`
4. La colección "CRM API - Colección Completa" aparecerá en tu sidebar

## Configurar Variables

### Variables de la Colección

La colección incluye dos variables que debes configurar:

1. **base_url**: URL base de tu API
   - Valor por defecto: `http://localhost:8000/api`
   - Para producción: `https://tu-dominio.com/api`

2. **api_token**: Tu token de autenticación
   - Valor: (vacío por defecto, debes configurarlo)

### Cómo Configurar las Variables

#### Opción 1: Configurar en la Colección (Recomendado)

1. Haz click derecho en la colección "CRM API - Colección Completa"
2. Selecciona **Edit**
3. Ve a la pestaña **Variables**
4. En la fila `api_token`, ingresa tu token en la columna **Current Value**
5. Haz click en **Save**

#### Opción 2: Usar Variables de Entorno

1. Crea un nuevo Environment (ícono de ojo en la esquina superior derecha)
2. Agrega las variables:
   - `base_url`: `http://localhost:8000/api`
   - `api_token`: tu_token_aqui
3. Selecciona el environment antes de hacer peticiones

## Obtener un Token de API

### Desde la Aplicación Web

1. Inicia sesión en el CRM
2. Ve a **Settings > API Tokens**
3. Haz click en **Create New Token**
4. Dale un nombre descriptivo (ej: "Postman Testing")
5. Copia el token generado
6. Pégalo en la variable `api_token` de Postman

### Desde la Base de Datos (Desarrollo)

Si estás en desarrollo y necesitas un token rápido:

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
$token = $user->createToken('postman-test')->plainTextToken;
echo $token;
```

## Estructura de la Colección

La colección está organizada en 4 carpetas principales:

### 1. Companies (Empresas)
- ✅ Listar Empresas
- ✅ Crear Empresa
- ✅ Obtener Empresa
- ✅ Actualizar Empresa
- ✅ Eliminar Empresa

### 2. Leads
- ✅ Listar Leads
- ✅ Crear Lead
- ✅ Obtener Lead
- ✅ Actualizar Lead
- ✅ Eliminar Lead
- ✅ Listar Estados de Leads

### 3. Quotations (Cotizaciones)
- ✅ Listar Cotizaciones
- ✅ Crear Cotización
- ✅ Obtener Cotización
- ✅ Actualizar Cotización
- ✅ Eliminar Cotización
- ✅ Actualizar Estado de Cotización
- ✅ Obtener Siguiente Número de Cotización

### 4. Calendar (Calendario)
- ✅ Listar Reuniones
- ✅ Agendar Reunión
- ✅ Cancelar Reunión

## Flujos de Trabajo Comunes

### Flujo 1: Crear una Empresa y un Lead

1. **Crear Empresa**
   - Carpeta: Companies > Crear Empresa
   - Copia el `id` de la respuesta

2. **Crear Lead**
   - Carpeta: Leads > Crear Lead
   - En el body, actualiza `company_id` con el ID de la empresa creada
   - Envía la petición

### Flujo 2: Crear Cotización para un Lead

1. **Obtener Siguiente Número**
   - Carpeta: Quotations > Obtener Siguiente Número de Cotización
   - Copia el `quotation_number` de la respuesta

2. **Crear Cotización**
   - Carpeta: Quotations > Crear Cotización
   - Actualiza `quotation_number` con el número obtenido
   - Actualiza `lead_id` con el ID de un lead existente
   - Envía la petición

3. **Actualizar Estado**
   - Carpeta: Quotations > Actualizar Estado de Cotización
   - Cambia el estado a "sent" para marcarla como enviada

### Flujo 3: Agendar Reunión con un Lead

1. **Listar Leads**
   - Carpeta: Leads > Listar Leads
   - Identifica el lead con quien quieres agendar

2. **Agendar Reunión**
   - Carpeta: Calendar > Agendar Reunión
   - Actualiza `lead_id` con el ID del lead
   - Actualiza `scheduled_at` con la fecha deseada
   - Envía la petición

3. **Ver Reuniones**
   - Carpeta: Calendar > Listar Reuniones
   - Verifica que la reunión fue agendada

## Ejemplos de Datos

### Crear Empresa

```json
{
  "business_name": "Tech Innovations SpA",
  "rut": "76888999-0",
  "fantasy_name": "TechInnov",
  "email": "contacto@techinnovations.cl",
  "phone": "+56912345678",
  "size": "medium",
  "industry": "Tecnología"
}
```

### Crear Lead

```json
{
  "name": "Carlos Rodríguez",
  "email": "carlos@empresa.cl",
  "phone": "+56987654321",
  "lead_status_id": 1,
  "source": "LinkedIn",
  "budget": 3000000
}
```

### Crear Cotización

```json
{
  "quotation_number": "COT-2024-001",
  "lead_id": 1,
  "client_name": "Carlos Rodríguez",
  "client_email": "carlos@empresa.cl",
  "issue_date": "2024-02-25",
  "valid_until": "2024-03-25",
  "items": [
    {
      "description": "Desarrollo de aplicación web",
      "quantity": 1,
      "unit_price": 2500000,
      "subtotal": 2500000
    }
  ],
  "tax_rate": 19
}
```

## Tips y Mejores Prácticas

### 1. Usar Variables para IDs

Después de crear un recurso, guarda su ID como variable:

1. En la pestaña **Tests** de la petición, agrega:
```javascript
pm.test("Save ID", function () {
    var jsonData = pm.response.json();
    pm.collectionVariables.set("last_company_id", jsonData.data.id);
});
```

2. Usa la variable en otras peticiones: `{{last_company_id}}`

### 2. Validar Respuestas

Agrega tests automáticos en la pestaña **Tests**:

```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has data", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
});
```

### 3. Organizar con Carpetas

Crea subcarpetas para diferentes escenarios:
- Testing
- Producción
- Desarrollo

### 4. Usar Pre-request Scripts

Para generar datos dinámicos:

```javascript
// Generar RUT aleatorio
const rut = Math.floor(Math.random() * 90000000) + 10000000;
pm.collectionVariables.set("random_rut", rut + "-" + (rut % 10));

// Generar email único
const timestamp = Date.now();
pm.collectionVariables.set("unique_email", `test${timestamp}@example.com`);
```

## Troubleshooting

### Error 401: Unauthorized
- Verifica que el token esté configurado correctamente
- Asegúrate de que el token no haya expirado
- Genera un nuevo token si es necesario

### Error 422: Validation Error
- Revisa los campos requeridos en el body
- Verifica que los tipos de datos sean correctos
- Consulta la documentación de la API

### Error 404: Not Found
- Verifica que el ID del recurso exista
- Asegúrate de que la URL sea correcta
- Revisa que `base_url` esté configurada correctamente

### Error de Conexión
- Verifica que el servidor esté corriendo (`php artisan serve`)
- Confirma que `base_url` apunte al servidor correcto
- Revisa tu firewall o configuración de red

## Exportar y Compartir

### Exportar la Colección

1. Haz click derecho en la colección
2. Selecciona **Export**
3. Elige el formato (recomendado: Collection v2.1)
4. Guarda el archivo

### Compartir con el Equipo

1. Sube el archivo JSON a tu repositorio
2. O usa Postman Workspaces para colaboración en tiempo real
3. Documenta las variables de entorno necesarias

## Recursos Adicionales

- [Documentación de Postman](https://learning.postman.com/docs/)
- [Variables en Postman](https://learning.postman.com/docs/sending-requests/variables/)
- [Tests en Postman](https://learning.postman.com/docs/writing-scripts/test-scripts/)
- [API Documentation](./API_DOCUMENTATION.md)
