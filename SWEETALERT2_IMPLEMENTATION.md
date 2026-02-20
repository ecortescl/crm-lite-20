# Implementación de SweetAlert2 para Notificaciones Toast

## Resumen

Se ha completado la migración de `vue-sonner` a `SweetAlert2` con estilos personalizados que imitan el diseño de shadcn-vue.

## Archivos Creados/Modificados

### 1. Composable Personalizado
**Archivo**: `resources/js/composables/useToast.ts`

Composable que encapsula SweetAlert2 con métodos convenientes:
- `toast(message, type)` - Método base
- `success(message)` - Notificación de éxito (verde)
- `error(message)` - Notificación de error (rojo)
- `info(message)` - Notificación informativa (azul)
- `warning(message)` - Notificación de advertencia (amarillo)

### 2. Estilos Personalizados
**Archivo**: `resources/css/sweetalert2-custom.css`

Estilos que imitan shadcn-vue:
- Usa variables CSS de shadcn-vue (`--radius`, `--border`, `--popover`, etc.)
- Posición: esquina superior derecha (`top-end`)
- Animaciones de entrada/salida desde la derecha
- Timer de 3 segundos con barra de progreso
- Colores contextuales según el tipo de notificación

### 3. Variables CSS Agregadas
**Archivo**: `resources/css/app.css`

Se agregaron variables para success y warning en modo claro y oscuro:
```css
/* Light mode */
--success: hsl(142 76% 36%);
--success-foreground: hsl(0 0% 98%);
--warning: hsl(38 92% 50%);
--warning-foreground: hsl(0 0% 98%);

/* Dark mode */
--success: hsl(142 70% 45%);
--success-foreground: hsl(0 0% 98%);
--warning: hsl(38 92% 50%);
--warning-foreground: hsl(0 0% 98%);
```

### 4. Importación en App
**Archivo**: `resources/js/app.ts`

Se importó el CSS personalizado:
```typescript
import '../css/sweetalert2-custom.css';
```

## Módulos Actualizados

Todos los módulos CRUD han sido actualizados para usar el nuevo composable:

1. ✅ **Leads** (`resources/js/pages/Leads/Index.vue`)
2. ✅ **Companies** (`resources/js/pages/Companies/Index.vue`)
3. ✅ **Users** (`resources/js/pages/Users/Index.vue`)
4. ✅ **Roles** (`resources/js/pages/Roles/Index.vue`)
5. ✅ **Permissions** (`resources/js/pages/Permissions/Index.vue`)
6. ✅ **LeadStatuses** (`resources/js/pages/LeadStatuses/Index.vue`)

## Uso

```typescript
import { useToast } from '@/composables/useToast'

const { success, error, info, warning } = useToast()

// En callbacks de Inertia
router.post(route('leads.store'), data, {
  onSuccess: () => {
    success('Lead creado exitosamente')
  },
  onError: () => {
    error('Error al crear el lead')
  }
})
```

## Características

- ✅ Toasts aparecen en la esquina superior derecha
- ✅ Animaciones suaves de entrada/salida
- ✅ Barra de progreso del timer
- ✅ Pausa al hacer hover
- ✅ Estilos consistentes con shadcn-vue
- ✅ Soporte para modo claro y oscuro
- ✅ Iconos contextuales según el tipo
- ✅ Duración de 3 segundos

## Instalación

El paquete ya está instalado:
```bash
npm install sweetalert2
```

## Verificación

Para verificar que todo funciona correctamente:

1. Ejecuta el servidor de desarrollo: `npm run dev`
2. Navega a cualquier módulo CRUD (Leads, Empresas, Usuarios, etc.)
3. Realiza una acción (crear, editar, eliminar)
4. Deberías ver las notificaciones toast en la esquina superior derecha con el estilo de shadcn-vue

## Notas Técnicas

- Se eliminó completamente la dependencia de `vue-sonner`
- Los toasts usan las mismas variables CSS que el resto de la aplicación
- Los colores se adaptan automáticamente al tema (claro/oscuro)
- La implementación es consistente en todos los módulos
