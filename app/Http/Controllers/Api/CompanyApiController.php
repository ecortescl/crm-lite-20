<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Companies",
 *     description="Endpoints para gestión de empresas"
 * )
 */
class CompanyApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/companies",
     *     summary="Listar empresas",
     *     description="Obtiene un listado paginado de todas las empresas",
     *     operationId="getCompanies",
     *     tags={"Companies"},
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
     *         description="Búsqueda por nombre o RUT",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de empresas",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Company")),
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
        $query = Company::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('fantasy_name', 'like', "%{$search}%")
                  ->orWhere('rut', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $companies = $query->latest()->paginate($perPage);

        return response()->json($companies);
    }

    /**
     * @OA\Post(
     *     path="/api/companies",
     *     summary="Crear empresa",
     *     description="Crea una nueva empresa en el sistema",
     *     operationId="createCompany",
     *     tags={"Companies"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"business_name", "rut"},
     *             @OA\Property(property="business_name", type="string", example="Empresa Demo SpA", description="Razón social"),
     *             @OA\Property(property="rut", type="string", example="76123456-7", description="RUT de la empresa"),
     *             @OA\Property(property="fantasy_name", type="string", example="Demo", description="Nombre de fantasía"),
     *             @OA\Property(property="giro", type="string", example="Servicios de tecnología", description="Giro comercial"),
     *             @OA\Property(property="email", type="string", format="email", example="contacto@demo.cl"),
     *             @OA\Property(property="phone", type="string", example="+56912345678"),
     *             @OA\Property(property="website", type="string", example="https://demo.cl"),
     *             @OA\Property(property="address", type="string", example="Av. Principal 123"),
     *             @OA\Property(property="commune", type="string", example="Santiago"),
     *             @OA\Property(property="city", type="string", example="Santiago"),
     *             @OA\Property(property="region", type="string", example="Metropolitana"),
     *             @OA\Property(property="notes", type="string", example="Cliente importante"),
     *             @OA\Property(property="size", type="string", enum={"small", "medium", "large", "enterprise"}, example="medium"),
     *             @OA\Property(property="industry", type="string", example="Tecnología")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Empresa creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Empresa creada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Company")
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
            'business_name' => 'required|string|max:255',
            'rut' => ['required', 'string', 'max:255', Rule::unique('companies', 'rut')->where('tenant_id', $tenantId)],
            'fantasy_name' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'commune' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'size' => 'nullable|in:small,medium,large,enterprise',
            'industry' => 'nullable|string|max:255',
        ]);

        $company = Company::create($validated);

        return response()->json([
            'message' => 'Empresa creada exitosamente',
            'data' => $company,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/companies/{id}",
     *     summary="Obtener empresa",
     *     description="Obtiene los detalles de una empresa específica",
     *     operationId="getCompany",
     *     tags={"Companies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la empresa",
     *         @OA\JsonContent(ref="#/components/schemas/Company")
     *     ),
     *     @OA\Response(response=404, description="Empresa no encontrada"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * @OA\Put(
     *     path="/api/companies/{id}",
     *     summary="Actualizar empresa",
     *     description="Actualiza los datos de una empresa existente",
     *     operationId="updateCompany",
     *     tags={"Companies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="business_name", type="string"),
     *             @OA\Property(property="rut", type="string"),
     *             @OA\Property(property="fantasy_name", type="string"),
     *             @OA\Property(property="giro", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="website", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="commune", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="region", type="string"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="size", type="string", enum={"small", "medium", "large", "enterprise"}),
     *             @OA\Property(property="industry", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Empresa actualizada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Company")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=404, description="Empresa no encontrada"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function update(Request $request, Company $company)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'business_name' => 'sometimes|required|string|max:255',
            'rut' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'rut')->where('tenant_id', $tenantId)->ignore($company->id),
            ],
            'fantasy_name' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'commune' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'size' => 'nullable|in:small,medium,large,enterprise',
            'industry' => 'nullable|string|max:255',
        ]);

        $company->update($validated);

        return response()->json([
            'message' => 'Empresa actualizada exitosamente',
            'data' => $company,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/companies/{id}",
     *     summary="Eliminar empresa",
     *     description="Elimina una empresa del sistema",
     *     operationId="deleteCompany",
     *     tags={"Companies"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Empresa eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Empresa no encontrada"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Empresa eliminada exitosamente',
        ]);
    }
}
