# Mejoras de CRUD - Completado

## Resumen

Se han aplicado mejoras modernas a todos los CRUDs del sistema siguiendo el patrón establecido en Leads. Todos los módulos ahora cuentan con búsqueda en tiempo real, filtros, dropdown menus para acciones, alertas de confirmación y notificaciones toast.

## Módulos Actualizados

### ✅ 1. Leads (Referencia)
- Búsqueda en tiempo real con debounce (500ms)
- Filtros por Estado y Usuario asignado
- DropdownMenu con iconos para acciones
- AlertDialog para confirmar eliminación
- Toast notifications (success/error)
- Paginación completa

### ✅ 2. Empresas (Companies)
**Backend (`app/Http/Controllers/CompanyController.php`):**
- Búsqueda por: razón social, RUT, nombre fantasía, email
- Filtro por región
- Filtro por tamaño de empresa
- Paginación con 15 registros por página

**Frontend (`resources/js/pages/Companies/Index.vue`):**
- Búsqueda en tiempo real con debounce
- 2 filtros: Región y Tamaño
- Botón "Limpiar filtros"
- DropdownMenu para acciones (Editar, Eliminar)
- AlertDialog para confirmación de eliminación
- Toast notifications
- Paginación con info de resultados
- Estado vacío: "No se encontraron empresas"

### ✅ 3. Usuarios (Users)
**Backend (`app/Http/Controllers/UserController.php`):**
- Búsqueda por: nombre, email
- Filtro por rol
- Paginación con 15 registros por página

**Frontend (`resources/js/pages/Users/Index.vue`):**
- Búsqueda en tiempo real con debounce
- Filtro por rol
- Botón "Limpiar filtros"
- DropdownMenu para acciones
- AlertDialog para confirmación
- Toast notifications
- Paginación completa
- Muestra roles como badges
- Estado vacío: "No se encontraron usuarios"

### ✅ 4. Roles
**Backend (`app/Http/Controllers/RoleController.php`):**
- Búsqueda por: nombre, descripción
- Contador de usuarios por rol (`withCount('users')`)
- Paginación con 15 registros por página

**Frontend (`resources/js/pages/Roles/Index.vue`):**
- Búsqueda en tiempo real con debounce
- DropdownMenu para acciones
- AlertDialog para confirmación
- Toast notifications
- Paginación completa
- Muestra cantidad de usuarios por rol
- Muestra primeros 3 permisos + contador
- Estado vacío: "No se encontraron roles"

### ✅ 5. Permisos (Permissions)
**Backend (`app/Http/Controllers/PermissionController.php`):**
- Búsqueda por: nombre, descripción
- Paginación con 15 registros por página

**Frontend (`resources/js/pages/Permissions/Index.vue`):**
- Búsqueda en tiempo real con debounce
- DropdownMenu para acciones
- AlertDialog para confirmación
- Toast notifications
- Paginación completa
- Estado vacío: "No se encontraron permisos"

### ✅ 6. Estados de Leads (LeadStatuses)
**Backend (`app/Http/Controllers/LeadStatusController.php`):**
- Búsqueda por: nombre
- Contador de leads por estado (`withCount('leads')`)
- Sin paginación (pocos registros, ordenados por `order`)

**Frontend (`resources/js/pages/LeadStatuses/Index.vue`):**
- Búsqueda en tiempo real con debounce
- DropdownMenu para acciones
- AlertDialog para confirmación
- Toast notifications
- Muestra cantidad de leads por estado
- Muestra color con preview visual
- Estado vacío: "No se encontraron estados"

## Características Implementadas

### 🔍 Búsqueda
- Debounce de 500ms para evitar requests excesivos
- Búsqueda en tiempo real sin necesidad de botón
- Placeholder descriptivo en cada módulo
- Icono de lupa (Search) en el input

### 🎯 Filtros
- Filtros específicos según el módulo
- Select components de shadcn-vue
- Opción "Todos" en cada filtro
- Botón "Limpiar filtros" que aparece solo cuando hay filtros activos
- Computed property `hasActiveFilters` para mostrar/ocultar el botón

### 🎨 Acciones Modernas
- DropdownMenu con icono MoreVertical (⋮)
- Iconos en cada opción del menú:
  - Pencil para Editar
  - Trash2 para Eliminar
- Separador antes de la opción destructiva
- Clase `text-destructive` en la opción de eliminar

### ⚠️ Confirmación de Eliminación
- AlertDialog de shadcn-vue
- Título: "¿Estás seguro?"
- Descripción con el nombre del elemento a eliminar en negrita
- Botones: "Cancelar" y "Eliminar" (rojo)
- Variable `deleteDialogOpen` para controlar el estado
- Variable `{entity}ToDelete` para almacenar el elemento

### 🔔 Notificaciones Toast
- Librería: vue-sonner
- Toast de éxito al crear/actualizar/eliminar
- Toast de error en caso de fallo
- Mensajes en español y descriptivos
- Configurado globalmente en `AppSidebarLayout.vue`

### 📄 Paginación
- Información: "Mostrando X a Y de Z resultados"
- Botones: "Anterior" y "Siguiente"
- Iconos: ChevronLeft y ChevronRight
- Botones deshabilitados cuando no hay más páginas
- Preserva filtros y búsqueda al cambiar de página
- `preserveState: true` y `preserveScroll: true`

### 🎭 Estados Vacíos
- Mensaje centrado cuando no hay resultados
- Texto: "No se encontraron {entidad}"
- Estilo: `text-muted-foreground`
- Colspan apropiado para cubrir todas las columnas

## Patrón de Código

### Backend (Controller)
```php
public function index(Request $request)
{
    $query = Model::query()->with('relations');

    // Búsqueda
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('field1', 'like', "%{$search}%")
              ->orWhere('field2', 'like', "%{$search}%");
        });
    }

    // Filtros
    if ($request->filled('filter')) {
        $query->where('field', $request->filter);
    }

    $items = $query->latest()->paginate(15)->withQueryString();

    return Inertia::render('Module/Index', [
        'items' => $items,
        'filters' => $request->only(['search', 'filter']),
    ]);
}
```

### Frontend (Vue Component)
```vue
<script setup lang="ts">
import { ref, reactive, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { toast } from 'vue-sonner'

const props = defineProps<{
  items: any
  filters: any
}>()

// Estados
const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const editingItem = ref<any>(null)
const itemToDelete = ref<any>(null)
const processing = ref(false)

// Filtros
const searchQuery = ref(props.filters?.search || '')
const filterField = ref(props.filters?.filter || 'all')

const hasActiveFilters = computed(() => {
  return searchQuery.value || filterField.value !== 'all'
})

// Debounced search
let searchTimeout: ReturnType<typeof setTimeout>
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

// Funciones
const applyFilters = () => {
  router.get(route('items.index'), {
    search: searchQuery.value || undefined,
    filter: filterField.value !== 'all' ? filterField.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const confirmDelete = (item: any) => {
  itemToDelete.value = item
  deleteDialogOpen.value = true
}

const deleteItem = () => {
  if (!itemToDelete.value) return

  router.delete(route('items.destroy', itemToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      itemToDelete.value = null
      toast.success('Elemento eliminado exitosamente')
    },
    onError: () => {
      toast.error('Error al eliminar el elemento')
    },
    preserveScroll: true,
  })
}
</script>
```

## Componentes Utilizados

### shadcn-vue
- `Card` - Contenedor de secciones
- `Table` - Tablas de datos
- `Badge` - Etiquetas de estado
- `Button` - Botones de acción
- `Dialog` - Modales de crear/editar
- `Input` - Campos de texto
- `Label` - Etiquetas de formulario
- `Select` - Selectores de filtros
- `DropdownMenu` - Menú de acciones
- `AlertDialog` - Confirmación de eliminación

### lucide-vue-next (Iconos)
- `Plus` - Crear nuevo
- `Search` - Búsqueda
- `X` - Limpiar filtros
- `MoreVertical` - Menú de acciones
- `Pencil` - Editar
- `Trash2` - Eliminar
- `ChevronLeft` - Página anterior
- `ChevronRight` - Página siguiente

### vue-sonner
- `toast.success()` - Notificación de éxito
- `toast.error()` - Notificación de error

## Notas Técnicas

1. **Debounce**: Se usa `setTimeout` con 500ms para evitar requests excesivos durante la escritura
2. **Preservación de Estado**: `preserveState: true` y `preserveScroll: true` en todas las navegaciones
3. **Query String**: `.withQueryString()` en el backend para mantener filtros en la URL
4. **Computed Properties**: `hasActiveFilters` para mostrar/ocultar el botón de limpiar filtros
5. **Validación**: Los filtros con valor "all" se envían como `undefined` para no contaminar la URL
6. **Toast Global**: Configurado en `AppSidebarLayout.vue` con `<Toaster />`

## Testing

Para probar las mejoras:

1. **Búsqueda**: Escribir en el campo de búsqueda y verificar que filtra después de 500ms
2. **Filtros**: Seleccionar diferentes opciones y verificar que se aplican correctamente
3. **Limpiar Filtros**: Aplicar filtros y verificar que el botón aparece y funciona
4. **Acciones**: Abrir el dropdown menu y verificar que las opciones funcionan
5. **Eliminación**: Intentar eliminar y verificar que aparece el AlertDialog
6. **Toast**: Crear, editar o eliminar y verificar que aparecen las notificaciones
7. **Paginación**: Navegar entre páginas y verificar que se mantienen los filtros

## Archivos Modificados

### Backend
- `app/Http/Controllers/CompanyController.php`
- `app/Http/Controllers/UserController.php`
- `app/Http/Controllers/RoleController.php`
- `app/Http/Controllers/PermissionController.php`
- `app/Http/Controllers/LeadStatusController.php`

### Frontend
- `resources/js/pages/Companies/Index.vue`
- `resources/js/pages/Users/Index.vue`
- `resources/js/pages/Roles/Index.vue`
- `resources/js/pages/Permissions/Index.vue`
- `resources/js/pages/LeadStatuses/Index.vue`

## Próximos Pasos (Opcional)

1. Agregar más filtros según necesidades del negocio
2. Implementar exportación a Excel/CSV
3. Agregar acciones masivas (selección múltiple)
4. Implementar ordenamiento por columnas
5. Agregar vista de detalles (modal o página separada)
