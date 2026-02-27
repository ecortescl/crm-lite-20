<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Leads",
 *     description="Endpoints para gestión de leads"
 * )
 */
class LeadApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/leads",
     *     summary="Listar leads",
     *     description="Obtiene un listado paginado de todos los leads del usuario autenticado",
     *     operationId="getLeads",
     *     tags={"Leads"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Búsqueda por nombre, email o teléfono",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por ID de estado",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de leads",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Lead")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function index(Request $request)
    {
        $query = Lead::with(['status', 'assignedUser', 'company']);

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_company', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('lead_status_id', $request->status);
        }

        $perPage = $request->input('per_page', 15);
        $leads = $query->latest()->paginate($perPage);

        return response()->json($leads);
    }

    /**
     * @OA\Post(
     *     path="/api/leads",
     *     summary="Crear lead",
     *     description="Crea un nuevo lead en el sistema. El lead se asociará automáticamente al usuario autenticado.",
     *     operationId="createLead",
     *     tags={"Leads"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "lead_status_id"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez", description="Nombre del lead"),
     *             @OA\Property(property="email", type="string", format="email", example="juan@example.com", description="Email del lead"),
     *             @OA\Property(property="phone", type="string", example="+56912345678", description="Teléfono del lead"),
     *             @OA\Property(property="contact_company", type="string", example="Empresa ABC", description="Nombre de la empresa (texto libre)"),
     *             @OA\Property(property="company_id", type="integer", example=1, description="ID de empresa existente (opcional)"),
     *             @OA\Property(property="notes", type="string", example="Cliente interesado en producto X", description="Notas adicionales"),
     *             @OA\Property(property="lead_status_id", type="integer", example=1, description="ID del estado del lead"),
     *             @OA\Property(property="assigned_to", type="integer", example=1, description="ID del usuario asignado"),
     *             @OA\Property(property="source", type="string", example="Website", description="Origen del lead"),
     *             @OA\Property(property="utm_source", type="string", example="google", description="UTM Source"),
     *             @OA\Property(property="utm_medium", type="string", example="cpc", description="UTM Medium"),
     *             @OA\Property(property="utm_campaign", type="string", example="summer_sale", description="UTM Campaign"),
     *             @OA\Property(property="utm_term", type="string", example="crm software", description="UTM Term"),
     *             @OA\Property(property="utm_content", type="string", example="banner_top", description="UTM Content"),
     *             @OA\Property(property="budget", type="number", format="float", example=5000.00, description="Presupuesto estimado"),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2024-12-25 10:00:00", description="Fecha de reunión agendada"),
     *             @OA\Property(property="meeting_notes", type="string", example="Reunión para presentar propuesta", description="Notas de la reunión")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lead creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lead creado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Lead")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function store(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'contact_company' => 'nullable|string|max:255',
            'company_id' => ['nullable', Rule::exists('companies', 'id')->where('tenant_id', $tenantId)],
            'notes' => 'nullable|string',
            'lead_status_id' => ['required', Rule::exists('lead_statuses', 'id')->where('tenant_id', $tenantId)],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('tenant_id', $tenantId)],
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'meeting_notes' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'quote_items' => 'nullable',
            'invoice_number' => 'nullable|string|max:255',
            'final_amount' => 'nullable|numeric|min:0',
            'closed_at' => 'nullable|date',
            'payment_status' => 'nullable|in:pending,partial,paid',
        ]);

        // Si no se especifica assigned_to, asignar al usuario autenticado
        if (!isset($validated['assigned_to'])) {
            $validated['assigned_to'] = auth()->id();
        }

        $lead = Lead::create($validated);
        $lead->load(['status', 'assignedUser', 'company']);

        return response()->json([
            'message' => 'Lead creado exitosamente',
            'data' => $lead,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/leads/{id}",
     *     summary="Obtener lead",
     *     description="Obtiene los detalles de un lead específico",
     *     operationId="getLead",
     *     tags={"Leads"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del lead",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del lead",
     *         @OA\JsonContent(ref="#/components/schemas/Lead")
     *     ),
     *     @OA\Response(response=404, description="Lead no encontrado"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function show(Lead $lead)
    {
        $lead->load(['status', 'assignedUser', 'company']);
        return response()->json($lead);
    }

    /**
     * @OA\Put(
     *     path="/api/leads/{id}",
     *     summary="Actualizar lead",
     *     description="Actualiza los datos de un lead existente",
     *     operationId="updateLead",
     *     tags={"Leads"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del lead",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="contact_company", type="string"),
     *             @OA\Property(property="company_id", type="integer"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="lead_status_id", type="integer"),
     *             @OA\Property(property="assigned_to", type="integer"),
     *             @OA\Property(property="source", type="string"),
     *             @OA\Property(property="budget", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lead actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lead actualizado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Lead")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=404, description="Lead no encontrado"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function update(Request $request, Lead $lead)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'contact_company' => 'nullable|string|max:255',
            'company_id' => ['nullable', Rule::exists('companies', 'id')->where('tenant_id', $tenantId)],
            'notes' => 'nullable|string',
            'lead_status_id' => ['sometimes', 'required', Rule::exists('lead_statuses', 'id')->where('tenant_id', $tenantId)],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('tenant_id', $tenantId)],
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'meeting_notes' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'quote_items' => 'nullable',
            'invoice_number' => 'nullable|string|max:255',
            'final_amount' => 'nullable|numeric|min:0',
            'closed_at' => 'nullable|date',
            'payment_status' => 'nullable|in:pending,partial,paid',
        ]);

        $lead->update($validated);
        $lead->load(['status', 'assignedUser', 'company']);

        return response()->json([
            'message' => 'Lead actualizado exitosamente',
            'data' => $lead,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/leads/{id}",
     *     summary="Eliminar lead",
     *     description="Elimina un lead del sistema",
     *     operationId="deleteLead",
     *     tags={"Leads"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del lead",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lead eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lead eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Lead no encontrado"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->json([
            'message' => 'Lead eliminado exitosamente',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/lead-statuses",
     *     summary="Listar estados de leads",
     *     description="Obtiene todos los estados disponibles para los leads",
     *     operationId="getLeadStatuses",
     *     tags={"Leads"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de estados",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nuevo"),
     *                 @OA\Property(property="color", type="string", example="#3b82f6"),
     *                 @OA\Property(property="order", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function statuses()
    {
        $statuses = LeadStatus::orderBy('order')->get();
        return response()->json($statuses);
    }
}
