<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Quotations",
 *     description="Endpoints para gestión de cotizaciones"
 * )
 */
class QuotationApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/quotations",
     *     summary="Listar cotizaciones",
     *     description="Obtiene un listado paginado de todas las cotizaciones",
     *     operationId="getQuotations",
     *     tags={"Quotations"},
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
     *         description="Búsqueda por número de cotización, nombre o RUT del cliente",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por estado",
     *         required=false,
     *         @OA\Schema(type="string", enum={"draft", "sent", "accepted", "rejected", "expired"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de cotizaciones",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Quotation")),
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
        $query = Quotation::with(['user', 'lead', 'company']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_rut', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 15);
        $quotations = $query->latest()->paginate($perPage);

        return response()->json($quotations);
    }

    /**
     * @OA\Post(
     *     path="/api/quotations",
     *     summary="Crear cotización",
     *     description="Crea una nueva cotización en el sistema",
     *     operationId="createQuotation",
     *     tags={"Quotations"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/QuotationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cotización creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cotización creada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Quotation")
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
            'quotation_number' => [
                'required',
                'string',
                Rule::unique('quotations', 'quotation_number')->where('tenant_id', $tenantId),
            ],
            'lead_id' => ['nullable', Rule::exists('leads', 'id')->where('tenant_id', $tenantId)],
            'company_id' => ['nullable', Rule::exists('companies', 'id')->where('tenant_id', $tenantId)],
            'client_name' => 'required|string|max:255',
            'client_rut' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'issue_date' => 'required|date',
            'valid_until' => 'required|date|after:issue_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $quotation = new Quotation($validated);
        $quotation->calculateTotals();
        $quotation->save();

        // Si hay un lead asociado, actualizar su quotation_id
        if ($validated['lead_id']) {
            Lead::where('id', $validated['lead_id'])->update(['quotation_id' => $quotation->id]);
        }

        $quotation->load(['user', 'lead', 'company']);

        return response()->json([
            'message' => 'Cotización creada exitosamente',
            'data' => $quotation,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/quotations/{id}",
     *     summary="Obtener cotización",
     *     description="Obtiene los detalles de una cotización específica",
     *     operationId="getQuotation",
     *     tags={"Quotations"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cotización",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la cotización",
     *         @OA\JsonContent(ref="#/components/schemas/Quotation")
     *     ),
     *     @OA\Response(response=404, description="Cotización no encontrada"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function show(Quotation $quotation)
    {
        $quotation->load(['user', 'lead', 'company']);
        return response()->json($quotation);
    }

    /**
     * @OA\Put(
     *     path="/api/quotations/{id}",
     *     summary="Actualizar cotización",
     *     description="Actualiza los datos de una cotización existente",
     *     operationId="updateQuotation",
     *     tags={"Quotations"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cotización",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/QuotationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cotización actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cotización actualizada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Quotation")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=404, description="Cotización no encontrada"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function update(Request $request, Quotation $quotation)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'quotation_number' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('quotations', 'quotation_number')->where('tenant_id', $tenantId)->ignore($quotation->id),
            ],
            'lead_id' => ['nullable', Rule::exists('leads', 'id')->where('tenant_id', $tenantId)],
            'company_id' => ['nullable', Rule::exists('companies', 'id')->where('tenant_id', $tenantId)],
            'client_name' => 'sometimes|required|string|max:255',
            'client_rut' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:500',
            'issue_date' => 'sometimes|required|date',
            'valid_until' => 'sometimes|required|date|after:issue_date',
            'items' => 'sometimes|required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'sometimes|required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'status' => 'nullable|in:draft,sent,accepted,rejected,expired',
        ]);

        $quotation->fill($validated);
        $quotation->calculateTotals();
        $quotation->save();

        $quotation->load(['user', 'lead', 'company']);

        return response()->json([
            'message' => 'Cotización actualizada exitosamente',
            'data' => $quotation,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/quotations/{id}",
     *     summary="Eliminar cotización",
     *     description="Elimina una cotización del sistema",
     *     operationId="deleteQuotation",
     *     tags={"Quotations"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cotización",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cotización eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cotización eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Cotización no encontrada"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function destroy(Quotation $quotation)
    {
        // Remover la referencia en leads si existe
        Lead::where('quotation_id', $quotation->id)->update(['quotation_id' => null]);
        
        $quotation->delete();

        return response()->json([
            'message' => 'Cotización eliminada exitosamente',
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/quotations/{id}/status",
     *     summary="Actualizar estado de cotización",
     *     description="Actualiza únicamente el estado de una cotización",
     *     operationId="updateQuotationStatus",
     *     tags={"Quotations"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cotización",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"draft", "sent", "accepted", "rejected", "expired"}, example="sent")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Estado actualizado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Quotation")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=404, description="Cotización no encontrada"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        $quotation->update(['status' => $validated['status']]);
        $quotation->load(['user', 'lead', 'company']);

        return response()->json([
            'message' => 'Estado actualizado exitosamente',
            'data' => $quotation,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/quotations/next-number",
     *     summary="Obtener siguiente número de cotización",
     *     description="Genera el siguiente número de cotización disponible",
     *     operationId="getNextQuotationNumber",
     *     tags={"Quotations"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Siguiente número de cotización",
     *         @OA\JsonContent(
     *             @OA\Property(property="quotation_number", type="string", example="COT-2024-001")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function nextNumber()
    {
        return response()->json([
            'quotation_number' => Quotation::generateQuotationNumber(),
        ]);
    }
}
