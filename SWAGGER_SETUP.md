# Configuración de Swagger para la API

## Instalación

Para habilitar la documentación interactiva de Swagger, sigue estos pasos:

### 1. Instalar el paquete l5-swagger

```bash
composer require darkaonline/l5-swagger
```

### 2. Publicar la configuración

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

### 3. Generar la documentación

```bash
php artisan l5-swagger:generate
```

### 4. Acceder a la documentación

Abre tu navegador y ve a:
```
http://localhost:8000/api/documentation
```

## Configuración Actual

La configuración de Swagger ya está lista en `config/l5-swagger.php` con:

- **Título:** CRM API Documentation
- **Ruta de documentación:** `/api/documentation`
- **Autenticación:** Bearer Token (Sanctum)
- **Anotaciones:** Ubicadas en `app/Http/Controllers/Api`

## Estructura de Anotaciones

Todos los controladores de la API ya tienen las anotaciones de Swagger configuradas:

### Controladores documentados:
- ✅ `CompanyApiController.php` - Gestión de empresas
- ✅ `LeadApiController.php` - Gestión de leads
- ✅ `QuotationApiController.php` - Gestión de cotizaciones
- ✅ `CalendarApiController.php` - Gestión de calendario
- ✅ `SwaggerController.php` - Schemas y configuración general

## Schemas Definidos

Los siguientes schemas están definidos en `SwaggerController.php`:

1. **Company** - Modelo de empresa
2. **Lead** - Modelo de lead
3. **Quotation** - Modelo de cotización
4. **QuotationRequest** - Request para crear/actualizar cotizaciones

## Regenerar Documentación

Cada vez que modifiques las anotaciones en los controladores, regenera la documentación:

```bash
php artisan l5-swagger:generate
```

## Configuración de Producción

Para producción, configura estas variables en tu `.env`:

```env
L5_SWAGGER_GENERATE_ALWAYS=false
L5_SWAGGER_CONST_HOST=https://tu-dominio.com/api
```

## Autenticación en Swagger UI

1. Genera un token de API desde el panel de administración
2. En Swagger UI, haz click en el botón "Authorize"
3. Ingresa tu token en el formato: `tu_token_aqui` (sin "Bearer")
4. Haz click en "Authorize" y luego "Close"
5. Ahora puedes probar todos los endpoints

## Troubleshooting

### Error: "There are no commands defined in the l5-swagger namespace"
- Solución: Instala el paquete con `composer require darkaonline/l5-swagger`

### La documentación no se actualiza
- Solución: Ejecuta `php artisan l5-swagger:generate` y limpia el cache con `php artisan config:clear`

### Error 404 en /api/documentation
- Solución: Verifica que el paquete esté instalado y publicado correctamente

## Recursos Adicionales

- [Documentación oficial de l5-swagger](https://github.com/DarkaOnLine/L5-Swagger)
- [Especificación OpenAPI 3.0](https://swagger.io/specification/)
- [Anotaciones de Swagger PHP](https://zircote.github.io/swagger-php/)
