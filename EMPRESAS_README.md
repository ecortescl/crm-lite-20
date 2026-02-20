# Sistema de Empresas

## Descripción

El CRM incluye un sistema completo de gestión de empresas con datos típicos usados en Chile.

## Características

### Campos de Empresa

**Información Legal:**
- Razón Social (obligatorio)
- RUT (obligatorio, único, formato chileno)
- Nombre de Fantasía (opcional)
- Giro (actividad económica)

**Contacto:**
- Email
- Teléfono
- Sitio Web

**Dirección:**
- Dirección completa
- Comuna
- Ciudad
- Región (selector con todas las regiones de Chile)

**Información Adicional:**
- Tamaño de empresa (Micro, Pequeña, Mediana, Grande)
- Industria/Sector
- Notas

### Asociación con Leads

Los leads pueden asociarse a empresas de **dos formas**:

1. **Empresa Registrada**: Seleccionar una empresa del sistema
   - Aparece con badge "Registrada" en la tabla
   - Permite acceso a todos los datos de la empresa
   - Ideal para clientes recurrentes

2. **Empresa de Texto Libre**: Escribir el nombre manualmente
   - Para empresas que aún no están registradas
   - Útil para leads nuevos o únicos
   - Se puede convertir a empresa registrada después

3. **Sin Empresa**: El lead puede no tener empresa asociada
   - Aparece como "Sin empresa" en la tabla
   - Útil para contactos personales o freelancers

### Formato de RUT

El sistema acepta RUT en cualquier formato:
- Con puntos y guión: `76.123.456-7`
- Sin formato: `761234567`
- Con K mayúscula o minúscula

El RUT se guarda limpio en la base de datos y se formatea automáticamente para mostrar.

### Regiones de Chile

El selector incluye todas las regiones:
- Región de Arica y Parinacota
- Región de Tarapacá
- Región de Antofagasta
- Región de Atacama
- Región de Coquimbo
- Región de Valparaíso
- Región Metropolitana
- Región del Libertador General Bernardo O'Higgins
- Región del Maule
- Región de Ñuble
- Región del Biobío
- Región de La Araucanía
- Región de Los Ríos
- Región de Los Lagos
- Región de Aysén
- Región de Magallanes

### Tamaños de Empresa

Según clasificación chilena:
- **Microempresa**: 1-9 trabajadores
- **Pequeña**: 10-49 trabajadores
- **Mediana**: 50-199 trabajadores
- **Grande**: 200+ trabajadores

## Uso

### Crear una Empresa

1. Ir a "Empresas" en el menú lateral
2. Click en "Crear Empresa"
3. Completar al menos Razón Social y RUT
4. Los demás campos son opcionales

### Asociar Lead a Empresa

Al crear o editar un lead:

1. **Opción 1 - Empresa Registrada:**
   - Seleccionar de la lista desplegable
   - El campo de texto libre se deshabilitará

2. **Opción 2 - Texto Libre:**
   - Dejar "Sin empresa asociada" en el selector
   - Escribir el nombre en el campo de texto

3. **Opción 3 - Sin Empresa:**
   - Dejar ambos campos vacíos

### Migrar de Texto a Empresa Registrada

1. Crear la empresa en el módulo de Empresas
2. Editar el lead
3. Seleccionar la empresa registrada
4. El campo de texto libre se limpiará automáticamente

## Migraciones

### Crear tablas

```bash
php artisan migrate
```

### Poblar con datos de ejemplo

```bash
php artisan db:seed --class=CompanySeeder
```

Esto creará 5 empresas de ejemplo con datos chilenos.

## Validaciones

- **RUT**: Debe ser único en el sistema
- **Email**: Formato válido de email
- **Website**: Formato válido de URL
- **Tamaño**: Solo valores permitidos (micro, small, medium, large)

## Notas Técnicas

- El campo `company` en la tabla `leads` fue renombrado a `contact_company`
- Se agregó `company_id` como foreign key nullable
- El modelo Lead tiene un accessor `company_name` que retorna el nombre correcto
- El modelo Company tiene accessors para `formatted_rut` y `display_name`
