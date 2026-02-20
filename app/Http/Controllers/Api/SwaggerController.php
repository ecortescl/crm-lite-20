<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="CRM API",
 *     version="1.0.0",
 *     description="API para gestión de CRM - Empresas y Leads",
 *     @OA\Contact(
 *         email="soporte@crm.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor API"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Ingresa tu token de API generado desde Settings > API Tokens"
 * )
 * 
 * @OA\Schema(
 *     schema="Company",
 *     type="object",
 *     title="Company",
 *     description="Modelo de Empresa",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="business_name", type="string", example="Empresa Demo SpA"),
 *     @OA\Property(property="rut", type="string", example="76123456-7"),
 *     @OA\Property(property="fantasy_name", type="string", example="Demo"),
 *     @OA\Property(property="giro", type="string", example="Servicios de tecnología"),
 *     @OA\Property(property="email", type="string", example="contacto@demo.cl"),
 *     @OA\Property(property="phone", type="string", example="+56912345678"),
 *     @OA\Property(property="website", type="string", example="https://demo.cl"),
 *     @OA\Property(property="address", type="string", example="Av. Principal 123"),
 *     @OA\Property(property="commune", type="string", example="Santiago"),
 *     @OA\Property(property="city", type="string", example="Santiago"),
 *     @OA\Property(property="region", type="string", example="Metropolitana"),
 *     @OA\Property(property="notes", type="string", example="Cliente importante"),
 *     @OA\Property(property="size", type="string", enum={"small", "medium", "large", "enterprise"}, example="medium"),
 *     @OA\Property(property="industry", type="string", example="Tecnología"),
 *     @OA\Property(property="display_name", type="string", example="Demo"),
 *     @OA\Property(property="formatted_rut", type="string", example="76.123.456-7"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z")
 * )
 * 
 * @OA\Schema(
 *     schema="Lead",
 *     type="object",
 *     title="Lead",
 *     description="Modelo de Lead",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="email", type="string", example="juan@example.com"),
 *     @OA\Property(property="phone", type="string", example="+56912345678"),
 *     @OA\Property(property="contact_company", type="string", example="Empresa ABC"),
 *     @OA\Property(property="company_id", type="integer", example=1, nullable=true),
 *     @OA\Property(property="notes", type="string", example="Cliente interesado en producto X"),
 *     @OA\Property(property="lead_status_id", type="integer", example=1),
 *     @OA\Property(property="assigned_to", type="integer", example=1, nullable=true),
 *     @OA\Property(property="source", type="string", example="Website"),
 *     @OA\Property(property="utm_source", type="string", example="google"),
 *     @OA\Property(property="utm_medium", type="string", example="cpc"),
 *     @OA\Property(property="utm_campaign", type="string", example="summer_sale"),
 *     @OA\Property(property="utm_term", type="string", example="crm software"),
 *     @OA\Property(property="utm_content", type="string", example="banner_top"),
 *     @OA\Property(property="budget", type="number", format="float", example=5000.00, nullable=true),
 *     @OA\Property(property="scheduled_at", type="string", format="date-time", example="2024-12-25T10:00:00.000000Z", nullable=true),
 *     @OA\Property(property="meeting_notes", type="string", example="Reunión para presentar propuesta"),
 *     @OA\Property(property="quote_items", type="object", nullable=true),
 *     @OA\Property(property="invoice_number", type="string", example="F-001234", nullable=true),
 *     @OA\Property(property="final_amount", type="number", format="float", example=4500.00, nullable=true),
 *     @OA\Property(property="closed_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="payment_status", type="string", enum={"pending", "partial", "paid"}, example="pending", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(
 *         property="status",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Nuevo"),
 *         @OA\Property(property="color", type="string", example="#3b82f6")
 *     ),
 *     @OA\Property(
 *         property="assigned_user",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Admin User"),
 *         @OA\Property(property="email", type="string", example="admin@crm.com")
 *     ),
 *     @OA\Property(
 *         property="company",
 *         type="object",
 *         nullable=true,
 *         ref="#/components/schemas/Company"
 *     )
 * )
 */
class SwaggerController extends Controller
{
    //
}
