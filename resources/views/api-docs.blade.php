<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        pre { background: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; }
        code { font-family: 'Courier New', monospace; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-bold mb-2">API Documentation</h1>
                    <p class="text-gray-600">Documentación completa de la API del CRM</p>
                </div>
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('api.documentation.postman') }}"
                        class="px-4 py-2 rounded-md bg-black text-white hover:opacity-90 transition"
                    >
                        Descargar Postman JSON
                    </a>
                    <a
                        href="{{ route('api-tokens.index') }}"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 transition"
                    >
                        Volver
                    </a>
                </div>
            </div>

            <!-- Autenticación -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold mb-4">🔐 Autenticación</h2>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="font-semibold">Todas las peticiones requieren autenticación mediante Bearer Token</p>
                </div>
                <p class="mb-4">Para obtener un token:</p>
                <ol class="list-decimal list-inside space-y-2 mb-4">
                    <li>Inicia sesión en el CRM</li>
                    <li>Ve a <strong>Settings > API Tokens</strong></li>
                    <li>Crea un nuevo token</li>
                    <li>Copia el token generado</li>
                </ol>
                <p class="mb-2 font-semibold">Uso del token:</p>
                <pre><code>Authorization: Bearer TU_TOKEN_AQUI
Accept: application/json</code></pre>
            </section>

            <!-- URL Base -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold mb-4">🌐 URL Base</h2>
                <pre><code>{{ url('/api') }}</code></pre>
            </section>

            <!-- Empresas -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold mb-4">🏢 Empresas (Companies)</h2>
                
                <!-- Listar -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Listar Empresas</h3>
                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded inline-block mb-2">GET</div>
                    <code class="ml-2">/api/companies</code>
                    
                    <p class="mt-4 mb-2 font-semibold">Parámetros de consulta:</p>
                    <ul class="list-disc list-inside mb-4">
                        <li><code>page</code> - Número de página (opcional)</li>
                        <li><code>per_page</code> - Registros por página (opcional, default: 15)</li>
                        <li><code>search</code> - Búsqueda por nombre o RUT (opcional)</li>
                    </ul>
                    
                    <p class="mb-2 font-semibold">Ejemplo:</p>
                    <pre><code>curl -X GET "{{ url('/api/companies?page=1') }}" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Accept: application/json"</code></pre>
                </div>

                <!-- Crear -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Crear Empresa</h3>
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded inline-block mb-2">POST</div>
                    <code class="ml-2">/api/companies</code>
                    
                    <p class="mt-4 mb-2 font-semibold">Campos requeridos:</p>
                    <ul class="list-disc list-inside mb-4">
                        <li><code>business_name</code> - Razón social</li>
                        <li><code>rut</code> - RUT de la empresa (único)</li>
                    </ul>
                    
                    <p class="mb-2 font-semibold">Ejemplo:</p>
                    <pre><code>curl -X POST "{{ url('/api/companies') }}" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "business_name": "Empresa Demo SpA",
    "rut": "76123456-7",
    "fantasy_name": "Demo",
    "email": "contacto@demo.cl",
    "phone": "+56912345678"
  }'</code></pre>
                </div>

                <!-- Obtener -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Obtener Empresa</h3>
                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded inline-block mb-2">GET</div>
                    <code class="ml-2">/api/companies/{id}</code>
                </div>

                <!-- Actualizar -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Actualizar Empresa</h3>
                    <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded inline-block mb-2">PUT</div>
                    <code class="ml-2">/api/companies/{id}</code>
                </div>

                <!-- Eliminar -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Eliminar Empresa</h3>
                    <div class="bg-red-100 text-red-800 px-3 py-1 rounded inline-block mb-2">DELETE</div>
                    <code class="ml-2">/api/companies/{id}</code>
                </div>
            </section>

            <!-- Leads -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold mb-4">👤 Leads</h2>
                
                <!-- Listar -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Listar Leads</h3>
                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded inline-block mb-2">GET</div>
                    <code class="ml-2">/api/leads</code>
                    
                    <p class="mt-4 mb-2 font-semibold">Parámetros de consulta:</p>
                    <ul class="list-disc list-inside mb-4">
                        <li><code>page</code> - Número de página (opcional)</li>
                        <li><code>per_page</code> - Registros por página (opcional, default: 15)</li>
                        <li><code>search</code> - Búsqueda por nombre, email o teléfono (opcional)</li>
                        <li><code>status</code> - Filtrar por ID de estado (opcional)</li>
                    </ul>
                </div>

                <!-- Crear -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Crear Lead</h3>
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded inline-block mb-2">POST</div>
                    <code class="ml-2">/api/leads</code>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 my-4">
                        <p class="font-semibold">⚠️ Nota importante:</p>
                        <p>Si no especificas <code>assigned_to</code>, el lead se asignará automáticamente al usuario dueño del token.</p>
                    </div>
                    
                    <p class="mt-4 mb-2 font-semibold">Campos requeridos:</p>
                    <ul class="list-disc list-inside mb-4">
                        <li><code>name</code> - Nombre del lead</li>
                        <li><code>lead_status_id</code> - ID del estado del lead</li>
                    </ul>
                    
                    <p class="mb-2 font-semibold">Ejemplo:</p>
                    <pre><code>curl -X POST "{{ url('/api/leads') }}" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "phone": "+56912345678",
    "lead_status_id": 1,
    "source": "Website",
    "budget": 5000.00
  }'</code></pre>
                </div>

                <!-- Estados -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-2">Listar Estados de Leads</h3>
                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded inline-block mb-2">GET</div>
                    <code class="ml-2">/api/lead-statuses</code>
                    <p class="mt-2 text-sm text-gray-600">Obtiene todos los estados disponibles para asignar a los leads</p>
                </div>
            </section>

            <!-- Respuestas -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold mb-4">📋 Respuestas de la API</h2>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">✅ Éxito (200/201)</h3>
                    <pre><code>{
  "message": "Lead creado exitosamente",
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    ...
  }
}</code></pre>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">❌ Error de Validación (422)</h3>
                    <pre><code>{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}</code></pre>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">🔒 No Autenticado (401)</h3>
                    <pre><code>{
  "message": "Unauthenticated."
}</code></pre>
                </div>
            </section>

            <!-- Códigos HTTP -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold mb-4">📊 Códigos de Estado HTTP</h2>
                <ul class="space-y-2">
                    <li><span class="font-mono bg-green-100 px-2 py-1 rounded">200</span> - Petición exitosa</li>
                    <li><span class="font-mono bg-green-100 px-2 py-1 rounded">201</span> - Recurso creado exitosamente</li>
                    <li><span class="font-mono bg-yellow-100 px-2 py-1 rounded">401</span> - Token inválido o no proporcionado</li>
                    <li><span class="font-mono bg-yellow-100 px-2 py-1 rounded">404</span> - Recurso no encontrado</li>
                    <li><span class="font-mono bg-yellow-100 px-2 py-1 rounded">422</span> - Error de validación</li>
                    <li><span class="font-mono bg-red-100 px-2 py-1 rounded">500</span> - Error del servidor</li>
                </ul>
            </section>

            <footer class="border-t pt-8 mt-12 text-center text-gray-600">
                <p>Para más información, consulta <a href="{{ url('/') }}" class="text-blue-600 hover:underline">API_DOCUMENTATION.md</a></p>
            </footer>
        </div>
    </div>
</body>
</html>
