# 📚 Índice de Documentación de la API del CRM

## 🎯 Inicio Rápido

¿Primera vez usando la API? Empieza aquí:

1. **[QUICK_START.md](./QUICK_START.md)** ⚡
   - Prueba la API en 5 minutos
   - Genera tu token
   - Ejemplos con cURL
   - Checklist de verificación

---

## 📖 Documentación Principal

### Para Desarrolladores

2. **[API_DOCUMENTATION.md](./API_DOCUMENTATION.md)** 📘
   - Referencia completa de todos los endpoints
   - Parámetros y respuestas detalladas
   - Códigos de estado HTTP
   - Ejemplos de uso avanzados
   - **Recomendado para**: Integración y desarrollo

### Resumen Ejecutivo

3. **[API_SUMMARY.md](./API_SUMMARY.md)** 📋
   - Vista general de la API
   - Lista de endpoints (21 total)
   - Características implementadas
   - Schemas de datos
   - Checklist de implementación
   - **Recomendado para**: Gerentes de proyecto y overview rápido

---

## 🛠️ Herramientas y Testing

### Postman

4. **[POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md)** 🚀
   - Guía completa de uso de Postman
   - Cómo importar la colección
   - Configurar variables
   - Flujos de trabajo comunes
   - Tips y mejores prácticas
   - **Recomendado para**: Testing y desarrollo

5. **[postman_collection.json](./postman_collection.json)** 📦
   - Colección completa con 21 peticiones
   - Variables preconfiguradas
   - Ejemplos de datos
   - **Importar en Postman para usar**

### Swagger / OpenAPI

6. **[SWAGGER_SETUP.md](./SWAGGER_SETUP.md)** 📚
   - Instalación de l5-swagger
   - Configuración paso a paso
   - Acceso a documentación interactiva
   - Troubleshooting
   - **Recomendado para**: Documentación interactiva

---

## 📂 Estructura de Archivos

```
.
├── API_INDEX.md                    ← Estás aquí
├── QUICK_START.md                  ← Inicio rápido
├── API_DOCUMENTATION.md            ← Documentación completa
├── API_SUMMARY.md                  ← Resumen ejecutivo
├── POSTMAN_GUIDE.md                ← Guía de Postman
├── postman_collection.json         ← Colección de Postman
├── SWAGGER_SETUP.md                ← Setup de Swagger
│
├── app/Http/Controllers/Api/
│   ├── CalendarApiController.php   ← Endpoints de calendario
│   ├── CompanyApiController.php    ← Endpoints de empresas
│   ├── LeadApiController.php       ← Endpoints de leads
│   ├── QuotationApiController.php  ← Endpoints de cotizaciones
│   └── SwaggerController.php       ← Schemas de Swagger
│
└── routes/api.php                  ← Definición de rutas
```

---

## 🎯 Guía por Caso de Uso

### "Quiero probar la API rápidamente"
→ [QUICK_START.md](./QUICK_START.md)

### "Necesito integrar la API en mi aplicación"
→ [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)

### "Quiero usar Postman para testing"
→ [POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md) + [postman_collection.json](./postman_collection.json)

### "Necesito documentación interactiva"
→ [SWAGGER_SETUP.md](./SWAGGER_SETUP.md)

### "Quiero un overview de la API"
→ [API_SUMMARY.md](./API_SUMMARY.md)

---

## 📊 Endpoints por Módulo

### 🏢 Empresas (Companies)
- 5 endpoints
- CRUD completo
- Búsqueda y filtros
- [Ver documentación →](./API_DOCUMENTATION.md#empresas-companies)

### 👥 Leads
- 6 endpoints
- CRUD completo + estados
- Búsqueda y filtros
- [Ver documentación →](./API_DOCUMENTATION.md#leads)

### 📄 Cotizaciones (Quotations)
- 7 endpoints
- CRUD completo
- Gestión de estados
- Generación de números
- [Ver documentación →](./API_DOCUMENTATION.md#cotizaciones-quotations)

### 📅 Calendario (Calendar)
- 3 endpoints
- Gestión de reuniones
- Filtros por fecha
- [Ver documentación →](./API_DOCUMENTATION.md#calendario-calendar)

---

## 🔐 Autenticación

Todos los endpoints requieren autenticación mediante Bearer Token (Laravel Sanctum).

**Generar token:**
1. Inicia sesión en el CRM
2. Settings > API Tokens
3. Crear nuevo token

**Usar token:**
```http
Authorization: Bearer tu_token_aqui
```

[Más información →](./API_DOCUMENTATION.md#autenticación)

---

## 📝 Ejemplos Rápidos

### Listar Empresas
```bash
curl -H "Authorization: Bearer TOKEN" \
     http://localhost:8000/api/companies
```

### Crear Lead
```bash
curl -X POST http://localhost:8000/api/leads \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Juan","lead_status_id":1}'
```

### Agendar Reunión
```bash
curl -X POST http://localhost:8000/api/calendar/meetings \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"lead_id":1,"scheduled_at":"2024-12-25 10:00:00"}'
```

[Más ejemplos →](./QUICK_START.md)

---

## 🎓 Recursos de Aprendizaje

### Nivel Principiante
1. [QUICK_START.md](./QUICK_START.md) - Primeros pasos
2. [POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md) - Testing básico
3. [API_SUMMARY.md](./API_SUMMARY.md) - Overview general

### Nivel Intermedio
1. [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) - Referencia completa
2. [SWAGGER_SETUP.md](./SWAGGER_SETUP.md) - Documentación interactiva
3. Ejemplos de integración en la documentación

### Nivel Avanzado
1. Código fuente de los controladores
2. Anotaciones de Swagger en el código
3. Personalización de endpoints

---

## 🔧 Configuración

### Variables de Entorno
```env
# En tu .env
SANCTUM_STATEFUL_DOMAINS=localhost:8000
SESSION_DOMAIN=localhost
```

### Rutas Disponibles
```bash
# Ver todas las rutas de la API
php artisan route:list --path=api
```

### Generar Documentación Swagger
```bash
# Instalar paquete
composer require darkaonline/l5-swagger

# Generar docs
php artisan l5-swagger:generate
```

[Más información →](./SWAGGER_SETUP.md)

---

## 🐛 Troubleshooting

### Problemas Comunes

**Error 401: Unauthorized**
- Verifica que el token sea válido
- Asegúrate de incluir el header Authorization
- [Solución →](./QUICK_START.md#error-401-unauthorized)

**Error 422: Validation Error**
- Revisa los campos requeridos
- Verifica los tipos de datos
- [Solución →](./API_DOCUMENTATION.md#códigos-de-estado)

**Error 404: Not Found**
- Verifica que el servidor esté corriendo
- Confirma la URL base
- [Solución →](./QUICK_START.md#error-404-not-found)

---

## 📞 Soporte

### Documentación
- [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) - Referencia completa
- [POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md) - Guía de testing
- [SWAGGER_SETUP.md](./SWAGGER_SETUP.md) - Setup de Swagger

### Contacto
- Email: soporte@crm.com
- Documentación: http://localhost:8000/api/documentation (después de instalar Swagger)

---

## ✅ Checklist de Implementación

- [x] 21 endpoints documentados
- [x] Autenticación con Sanctum
- [x] Validaciones completas
- [x] Colección de Postman
- [x] Anotaciones de Swagger
- [x] Documentación en Markdown
- [x] Ejemplos de uso
- [x] Guías de inicio rápido

---

## 🎉 ¡Todo Listo!

La API está completamente documentada y lista para usar. Elige tu punto de partida según tu necesidad:

- **Desarrollador nuevo**: [QUICK_START.md](./QUICK_START.md)
- **Integración**: [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- **Testing**: [POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md)
- **Overview**: [API_SUMMARY.md](./API_SUMMARY.md)

**¡Feliz desarrollo! 🚀**

---

## 📅 Última Actualización

**Fecha**: 25 de Febrero, 2026
**Versión**: 1.0.0
**Endpoints**: 21
**Módulos**: 4 (Companies, Leads, Quotations, Calendar)
