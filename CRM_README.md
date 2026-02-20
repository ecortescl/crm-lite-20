# CRM Lite - Sistema de Gestión de Leads

Sistema CRM simplificado construido con Laravel, Vue 3, Inertia.js y shadcn-vue.

## Características

### 1. Dashboard
- Resumen visual de leads por estado con iconos
- Cards compactos con contador de leads en cada etapa
- Kanban minimalista integrado con vista previa de leads
- Acceso rápido al Kanban completo

### 2. Gestión de Leads
- **Vista de Lista**: CRUD completo de leads con tabla paginada
- **Vista Kanban Mejorada**: 
  - Tablero visual con drag & drop fluido
  - Click en cualquier lead para ver ficha completa
  - Modal con información detallada según el estado
- Campos básicos: Nombre, Email, Teléfono, Empresa, Notas, Estado, Usuario Asignado

### 3. Ficha Completa de Lead (Modal)
Información contextual según el estado del lead:

- **Reunión/Agendamiento**:
  - Fecha y hora de la reunión
  - Notas de la reunión
  - Agenda y temas a tratar

- **Negociación**:
  - Presupuesto estimado
  - Items cotizados (productos/servicios)
  - Detalles de la propuesta

- **Concretado/Cierre**:
  - Número de factura
  - Monto final del negocio
  - Estado de pago (Pendiente, Parcial, Pagado)
  - Fecha de cierre

### 4. Estados de Leads (Personalizables)
Estados por defecto:
- Nuevo registro (Azul) - Lead inicial
- Contactado (Púrpura) - Primer contacto realizado
- Descartado (Rojo) - Lead no viable
- Reunión (Naranja) - Con campos de agendamiento
- Negociación (Verde) - Con presupuesto y cotización
- Concretado (Verde oscuro) - Con factura y pago

Puedes agregar, editar o eliminar estados según tus necesidades.

### 4. Sistema de Usuarios, Roles y Permisos
- **Usuarios**: Gestión completa de usuarios del sistema
- **Roles**: Crear roles personalizados (ej: Admin, Vendedor, Manager)
- **Permisos**: Control granular de acceso
  - view_leads
  - create_leads
  - edit_leads
  - delete_leads
  - manage_users
  - manage_roles
  - manage_permissions
  - manage_lead_statuses

## Instalación

### Requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (o MySQL/PostgreSQL)

### Pasos

1. **Instalar dependencias**
```bash
composer install
npm install
```

2. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configurar base de datos**
```bash
# Ya está configurado con SQLite
# Si prefieres MySQL/PostgreSQL, edita .env
```

4. **Ejecutar migraciones y seeders**
```bash
php artisan migrate:fresh --seed
```

Esto creará:
- Tablas necesarias
- Estados de leads por defecto
- Roles y permisos
- Usuario administrador: admin@example.com / password
- Leads de ejemplo

5. **Compilar assets**
```bash
npm run build
# o para desarrollo:
npm run dev
```

6. **Iniciar servidor**
```bash
php artisan serve
```

Visita: http://localhost:8000

## Credenciales por Defecto

- **Email**: admin@example.com
- **Password**: password

## Estructura del Proyecto

### Backend (Laravel)
```
app/
├── Models/
│   ├── Lead.php
│   ├── LeadStatus.php
│   ├── User.php
│   ├── Role.php
│   └── Permission.php
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── LeadController.php
│   ├── LeadStatusController.php
│   ├── UserController.php
│   ├── RoleController.php
│   └── PermissionController.php
```

### Frontend (Vue 3)
```
resources/js/
├── pages/
│   ├── Dashboard/Index.vue
│   ├── Leads/
│   │   ├── Index.vue (Lista)
│   │   └── Kanban.vue (Tablero)
│   ├── Users/Index.vue
│   ├── Roles/Index.vue
│   ├── Permissions/Index.vue
│   └── LeadStatuses/Index.vue
└── components/ui/ (shadcn-vue)
```

## Rutas Principales

- `/dashboard` - Dashboard principal
- `/leads` - Lista de leads
- `/leads/kanban` - Vista Kanban
- `/users` - Gestión de usuarios
- `/roles` - Gestión de roles
- `/permissions` - Gestión de permisos
- `/lead-statuses` - Gestión de estados
- `/settings/platform` - Configuración de la plataforma (nombre y logo)

## Uso

### Crear un Lead
1. Ve a "Leads" en el menú
2. Click en "Crear Lead"
3. Completa el formulario
4. Asigna un estado y usuario

### Mover Leads en Kanban
1. Ve a "Kanban" en el menú
2. Arrastra y suelta los leads entre columnas
3. Los cambios se guardan automáticamente

### Personalizar Estados
1. Ve a "Estados" en el menú
2. Agrega, edita o elimina estados
3. Personaliza colores y orden

### Gestionar Permisos
1. Ve a "Roles" en el menú
2. Crea o edita un rol
3. Selecciona los permisos deseados
4. Asigna roles a usuarios en "Usuarios"

### Personalizar la Plataforma
1. Ve a "Settings" en el menú de usuario
2. Selecciona "Platform"
3. Cambia el nombre de la plataforma (por defecto: "CRM landings.cl")
4. Opcionalmente, agrega la URL de tu logo personalizado
5. Los cambios se reflejarán en el sidebar y título de la aplicación

## Tecnologías Utilizadas

- **Backend**: Laravel 12
- **Frontend**: Vue 3 + TypeScript
- **UI**: shadcn-vue (componentes basados en Radix Vue)
- **Routing**: Inertia.js
- **Styling**: Tailwind CSS
- **Icons**: Lucide Vue

## Próximas Mejoras Sugeridas

- [ ] Filtros avanzados en lista de leads
- [ ] Exportación de datos (CSV, Excel)
- [ ] Historial de cambios de estado
- [ ] Notificaciones por email
- [ ] Dashboard con gráficos
- [ ] Búsqueda global
- [ ] Actividades y notas por lead
- [ ] Integración con calendario
- [ ] API REST para integraciones

## Soporte

Para problemas o preguntas, revisa la documentación de:
- [Laravel](https://laravel.com/docs)
- [Vue 3](https://vuejs.org/)
- [Inertia.js](https://inertiajs.com/)
- [shadcn-vue](https://www.shadcn-vue.com/)
