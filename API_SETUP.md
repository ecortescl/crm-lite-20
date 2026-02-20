# Configuración de la API con Laravel Sanctum

## Pasos de Instalación

### 1. Instalar Dependencias

```bash
composer require laravel/sanctum darkaonline/l5-swagger
```

### 2. Ejecutar Migraciones

```bash
php artisan migrate
```

Esto creará la tabla `personal_access_tokens` necesaria para Sanctum.

### 3. Publicar Configuración de Swagger (Opcional)

```bash
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
```

### 4. Generar Documentación Swagger

```bash
php artisan l5-swagger:generate
```

### 5. Configurar Variables de Entorno

Agrega estas líneas a tu archivo `.env`:

```env
# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:3000

# L5 Swagger
L5_SWAGGER_CONST_HOST=http://localhost:8000/api
```

Para producción, actualiza `L5_SWAGGER_CONST_HOST` con tu dominio real:

```env
L5_SWAGGER_CONST_HOST=https://tu-dominio.com/api
```

### 6. Configurar CORS (si es necesario)

Si tu frontend está en un dominio diferente, actualiza `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],

'allowed_origins' => [
    'http://localhost:3000',
    'https://tu-frontend.com',
],

'supports_credentials' => true,
```

### 7. Verificar Rutas

Verifica que las rutas de API estén registradas:

```bash
php artisan route:list --path=api
```

Deberías ver rutas como:
- `GET|HEAD  api/companies`
- `POST      api/companies`
- `GET|HEAD  api/leads`
- `POST      api/leads`
- etc.

### 8. Acceder a la Documentación

Una vez configurado, accede a:

- **Swagger UI**: `http://localhost:8000/api/documentation`
- **JSON Spec**: `http://localhost:8000/api/documentation/json`

## Uso Básico

### 1. Crear un Token desde la UI

1. Inicia sesión en el CRM
2. Ve a **Settings > API Tokens**
3. Crea un nuevo token
4. Copia el token generado

### 2. Probar la API

```bash
# Listar leads
curl -X GET "http://localhost:8000/api/leads" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"

# Crear una empresa
curl -X POST "http://localhost:8000/api/companies" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "business_name": "Mi Empresa",
    "rut": "12345678-9"
  }'
```

## Configuración de L5-Swagger

Crea el archivo `config/l5-swagger.php` si no existe:

```php
<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'CRM API Documentation',
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'docs' => storage_path('api-docs'),
                'docs_json' => 'api-docs.json',
                'annotations' => [
                    base_path('app/Http/Controllers/Api'),
                ],
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
        ],
        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
            'excludes' => [],
        ],
        'scanOptions' => [
            'analyser' => null,
            'analysis' => null,
            'processors' => [],
            'pattern' => null,
            'exclude' => [],
        ],
        'securityDefinitions' => [
            'securitySchemes' => [
                'sanctum' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'Token',
                ],
            ],
            'security' => [
                ['sanctum' => []],
            ],
        ],
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url' => null,
        'ui' => [
            'display' => [
                'dark_mode' => env('L5_SWAGGER_UI_DARK_MODE', false),
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter' => env('L5_SWAGGER_UI_FILTERS', true),
            ],
            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
            ],
        ],
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000/api'),
        ],
    ],
];
```

## Troubleshooting

### Error: "Class 'Laravel\Sanctum\HasApiTokens' not found"

Ejecuta:
```bash
composer require laravel/sanctum
```

### Error: "Table 'personal_access_tokens' doesn't exist"

Ejecuta:
```bash
php artisan migrate
```

### Swagger no genera documentación

Ejecuta:
```bash
php artisan l5-swagger:generate
```

Si persiste el error, verifica que las anotaciones en los controladores estén correctas.

### Token no funciona

Verifica que:
1. El token esté en el header: `Authorization: Bearer TOKEN`
2. El header `Accept: application/json` esté presente
3. El token no haya sido eliminado desde Settings

## Seguridad

- **Nunca** compartas tus tokens de API
- Revoca tokens que ya no uses
- Usa HTTPS en producción
- Considera implementar rate limiting adicional si es necesario
- Los tokens no expiran automáticamente, gestiónalos manualmente

## Próximos Pasos

1. Lee la documentación completa en `API_DOCUMENTATION.md`
2. Explora la documentación Swagger en `/api/documentation`
3. Prueba los endpoints con Postman o curl
4. Integra la API en tus aplicaciones
