# 📝 Notas Técnicas del Build Docker

## Problema con Wayfinder

### Descripción del Problema

El plugin `@laravel/vite-plugin-wayfinder` intenta ejecutar comandos de Laravel durante el build de Vite para generar rutas automáticamente. Esto causa problemas en el build de Docker porque:

1. Wayfinder necesita acceso a la aplicación Laravel completa
2. Requiere que la base de datos esté disponible
3. Intenta ejecutar comandos Artisan durante el build de assets

### Solución Implementada

Hemos implementado una solución en dos partes:

#### 1. Build Condicional (vite.config.ts)

```typescript
const isDockerBuild = process.env.DOCKER_BUILD === 'true';

export default defineConfig({
    plugins: [
        // ... otros plugins
        ...(!isDockerBuild ? [wayfinder({
            formVariants: true,
        })] : []),
    ],
});
```

Durante el build de Docker, la variable `DOCKER_BUILD=true` deshabilita el plugin de Wayfinder.

#### 2. Generación en Runtime (entrypoint.sh)

```bash
# Generar rutas de Wayfinder después del despliegue
php artisan wayfinder:generate || echo "⚠️  Wayfinder generation skipped"
```

Las rutas de Wayfinder se generan cuando el contenedor inicia, después de que:
- La base de datos esté disponible
- Las migraciones se hayan ejecutado
- Laravel esté completamente configurado

## Flujo de Build

### Durante la Construcción de la Imagen

1. **Instalar dependencias del sistema** (PHP, Node.js, PostgreSQL client, etc.)
2. **Copiar código fuente**
3. **Crear .env temporal** (con APP_KEY temporal)
4. **Instalar dependencias de Composer**
5. **Instalar dependencias de npm** (incluyendo devDependencies)
6. **Build de assets con Vite** (con `DOCKER_BUILD=true`, sin Wayfinder)
7. **Limpiar archivos temporales** (node_modules, .env temporal)

### Durante el Inicio del Contenedor

1. **Esperar a PostgreSQL**
2. **Crear enlace simbólico de storage**
3. **Ejecutar migraciones**
4. **Generar rutas de Wayfinder** ← Aquí se genera
5. **Optimizar caches**
6. **Configurar permisos**
7. **Iniciar servicios** (Nginx, PHP-FPM, Queue Workers)

## Ventajas de Este Enfoque

### ✅ Pros

1. **Build más rápido**: No necesita base de datos durante el build
2. **Más confiable**: No depende de comandos externos durante el build
3. **Portable**: La imagen puede construirse en cualquier entorno
4. **Actualización automática**: Las rutas se regeneran en cada despliegue
5. **Sin dependencias externas**: No requiere servicios adicionales durante el build

### ⚠️ Consideraciones

1. **Primer inicio más lento**: La generación de rutas añade ~5-10 segundos al inicio
2. **Requiere reinicio**: Si cambias rutas, necesitas reiniciar el contenedor
3. **Cache de rutas**: Las rutas se cachean después de la primera generación

## Alternativas Consideradas

### Opción 1: Multi-stage Build con Laravel (Descartada)

```dockerfile
# Stage 1: Build assets con Laravel disponible
FROM php:8.2-fpm-alpine AS builder
# Instalar todo, incluir base de datos temporal
# Build con Wayfinder habilitado
```

**Problema**: Requiere base de datos durante el build, hace el proceso más complejo y lento.

### Opción 2: Pre-generar Rutas (Descartada)

Generar rutas localmente y commitearlas al repositorio.

**Problema**: Las rutas pueden quedar desactualizadas, requiere regeneración manual.

### Opción 3: Configuración Vite Separada (Descartada)

Crear `vite.config.docker.ts` sin Wayfinder.

**Problema**: Duplicación de configuración, difícil de mantener.

### Opción 4: Variable de Entorno (Implementada) ✅

Usar `DOCKER_BUILD=true` para deshabilitar condicionalmente Wayfinder.

**Ventajas**: 
- Simple
- Mantenible
- Una sola configuración
- Fácil de entender

## Troubleshooting

### Error: "Wayfinder routes not found"

Si ves este error en runtime:

```bash
# Regenerar rutas manualmente
docker-compose exec app php artisan wayfinder:generate

# Limpiar cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Build falla con error de Wayfinder

Verifica que la variable `DOCKER_BUILD` esté configurada:

```dockerfile
ENV DOCKER_BUILD=true
RUN npm run build
```

### Rutas no se actualizan

Las rutas se cachean. Para forzar regeneración:

```bash
docker-compose exec app php artisan wayfinder:generate --force
docker-compose restart app
```

## Desarrollo Local vs Docker

### Desarrollo Local (sin Docker)

```bash
npm run dev
```

- Wayfinder habilitado
- Hot reload
- Regeneración automática de rutas

### Docker Build

```bash
docker-compose build
```

- Wayfinder deshabilitado durante build
- Assets pre-compilados
- Rutas generadas en runtime

## Mejoras Futuras

### Posibles Optimizaciones

1. **Cache de rutas de Wayfinder**: Cachear rutas entre builds si no cambian
2. **Build paralelo**: Separar build de assets y PHP en stages paralelos
3. **Pre-warming**: Pre-generar rutas comunes durante el build
4. **Lazy loading**: Generar rutas bajo demanda en lugar de todas al inicio

### Monitoreo

Agregar métricas para:
- Tiempo de generación de rutas
- Tamaño del cache de rutas
- Frecuencia de regeneración

## Referencias

- [Laravel Wayfinder Documentation](https://github.com/laravel/wayfinder)
- [Vite Plugin API](https://vitejs.dev/guide/api-plugin.html)
- [Docker Multi-stage Builds](https://docs.docker.com/build/building/multi-stage/)
- [Laravel Optimization](https://laravel.com/docs/deployment#optimization)

---

**Última actualización**: 2026-02-20  
**Versión de Wayfinder**: 0.1.9  
**Versión de Vite**: 7.0.4
