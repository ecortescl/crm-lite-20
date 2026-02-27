<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Calendar",
 *     description="Endpoints para gestión de calendario y reuniones"
 * )
 */
class CalendarApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/calendar/meetings",
     *     summary="Listar reuniones",
     *     description="Obtiene todas las reuniones agendadas del usuario autenticado",
     *     operationId="getMeetings",
     *     tags={"Calendar"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de reuniones",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Lead")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function meetings(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user?->isJefatura() ?? false;

        $meetingStatusId = LeadStatus::whereRaw('LOWER(name) like ?', ['%reuni%'])
            ->orderBy('order')
            ->value('id');

        $query = Lead::with(['assignedUser', 'company', 'status'])
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at');

        if ($meetingStatusId) {
            $query->where('lead_status_id', $meetingStatusId);
        }

        if (!$isAdmin && $user) {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhere('scheduled_by', $user->id);
            });
        }

        // Filtros de fecha
        if ($request->filled('start_date')) {
            $query->where('scheduled_at', '>=', $request->start_date . ' 00:00:00');
        }

        if ($request->filled('end_date')) {
            $query->where('scheduled_at', '<=', $request->end_date . ' 23:59:59');
        }

        $meetings = $query->get();

        return response()->json($meetings);
    }

    /**
     * @OA\Post(
     *     path="/api/calendar/meetings",
     *     summary="Agendar reunión",
     *     description="Crea o actualiza una reunión para un lead existente",
     *     operationId="scheduleMeeting",
     *     tags={"Calendar"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"lead_id", "scheduled_at"},
     *             @OA\Property(property="lead_id", type="integer", example=1, description="ID del lead"),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2024-12-25 10:00:00", description="Fecha y hora de la reunión"),
     *             @OA\Property(property="meeting_notes", type="string", example="Reunión para presentar propuesta", description="Notas de la reunión"),
     *             @OA\Property(property="meeting_link", type="string", example="https://meet.google.com/abc-defg-hij", description="Link de la reunión virtual")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reunión agendada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reunión agendada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Lead")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=404, description="Lead no encontrado"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function scheduleMeeting(Request $request)
    {
        $tenantId = $request->user()?->tenant_id;

        $validated = $request->validate([
            'lead_id' => ['required', Rule::exists('leads', 'id')->where('tenant_id', $tenantId)],
            'scheduled_at' => 'required|date',
            'meeting_notes' => 'nullable|string',
            'meeting_link' => 'nullable|string|max:2048',
        ]);

        $lead = Lead::findOrFail($validated['lead_id']);

        $lead->update([
            'scheduled_at' => $validated['scheduled_at'],
            'scheduled_by' => auth()->id(),
            'meeting_notes' => $validated['meeting_notes'] ?? $lead->meeting_notes,
            'meeting_link' => $validated['meeting_link'] ?? $lead->meeting_link,
        ]);

        $lead->load(['assignedUser', 'company', 'status']);

        return response()->json([
            'message' => 'Reunión agendada exitosamente',
            'data' => $lead,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/calendar/meetings/{lead_id}",
     *     summary="Cancelar reunión",
     *     description="Cancela una reunión agendada eliminando la fecha programada del lead",
     *     operationId="cancelMeeting",
     *     tags={"Calendar"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="lead_id",
     *         in="path",
     *         description="ID del lead",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reunión cancelada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reunión cancelada exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Lead no encontrado"),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function cancelMeeting($leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $lead->update([
            'scheduled_at' => null,
            'scheduled_by' => null,
        ]);

        return response()->json([
            'message' => 'Reunión cancelada exitosamente',
        ]);
    }
}
