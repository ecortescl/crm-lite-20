<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="CRM API",
 *     version="1.0.0",
 *     description="API para gestión de CRM - Empresas, Leads, Cotizaciones y Calendario",
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
 * 
 * @OA\Schema(
 *     schema="Quotation",
 *     type="object",
 *     title="Quotation",
 *     description="Modelo de Cotización",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="quotation_number", type="string", example="COT-2024-001"),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="lead_id", type="integer", example=1, nullable=true),
 *     @OA\Property(property="company_id", type="integer", example=1, nullable=true),
 *     @OA\Property(property="client_name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="client_rut", type="string", example="12345678-9", nullable=true),
 *     @OA\Property(property="client_email", type="string", example="juan@example.com", nullable=true),
 *     @OA\Property(property="client_phone", type="string", example="+56912345678", nullable=true),
 *     @OA\Property(property="client_address", type="string", example="Av. Principal 123", nullable=true),
 *     @OA\Property(property="issue_date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="valid_until", type="string", format="date", example="2024-02-15"),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="description", type="string", example="Servicio de consultoría"),
 *             @OA\Property(property="quantity", type="number", example=10),
 *             @OA\Property(property="unit_price", type="number", example=50000),
 *             @OA\Property(property="subtotal", type="number", example=500000)
 *         )
 *     ),
 *     @OA\Property(property="subtotal", type="number", format="float", example=500000.00),
 *     @OA\Property(property="tax_rate", type="number", format="float", example=19.00),
 *     @OA\Property(property="tax_amount", type="number", format="float", example=95000.00),
 *     @OA\Property(property="total", type="number", format="float", example=595000.00),
 *     @OA\Property(property="notes", type="string", example="Notas adicionales", nullable=true),
 *     @OA\Property(property="terms", type="string", example="Términos y condiciones", nullable=true),
 *     @OA\Property(property="status", type="string", enum={"draft", "sent", "accepted", "rejected", "expired"}, example="draft"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Admin User"),
 *         @OA\Property(property="email", type="string", example="admin@crm.com")
 *     ),
 *     @OA\Property(property="lead", type="object", ref="#/components/schemas/Lead", nullable=true),
 *     @OA\Property(property="company", type="object", ref="#/components/schemas/Company", nullable=true)
 * )
 * 
 * @OA\Schema(
 *     schema="QuotationRequest",
 *     type="object",
 *     title="QuotationRequest",
 *     description="Datos para crear o actualizar una cotización",
 *     required={"quotation_number", "client_name", "issue_date", "valid_until", "items", "tax_rate"},
 *     @OA\Property(property="quotation_number", type="string", example="COT-2024-001", description="Número único de cotización"),
 *     @OA\Property(property="lead_id", type="integer", example=1, nullable=true, description="ID del lead asociado"),
 *     @OA\Property(property="company_id", type="integer", example=1, nullable=true, description="ID de la empresa asociada"),
 *     @OA\Property(property="client_name", type="string", example="Juan Pérez", description="Nombre del cliente"),
 *     @OA\Property(property="client_rut", type="string", example="12345678-9", nullable=true, description="RUT del cliente"),
 *     @OA\Property(property="client_email", type="string", format="email", example="juan@example.com", nullable=true),
 *     @OA\Property(property="client_phone", type="string", example="+56912345678", nullable=true),
 *     @OA\Property(property="client_address", type="string", example="Av. Principal 123", nullable=true),
 *     @OA\Property(property="issue_date", type="string", format="date", example="2024-01-15", description="Fecha de emisión"),
 *     @OA\Property(property="valid_until", type="string", format="date", example="2024-02-15", description="Fecha de vencimiento"),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         description="Items de la cotización",
 *         @OA\Items(
 *             required={"description", "quantity", "unit_price", "subtotal"},
 *             @OA\Property(property="description", type="string", example="Servicio de consultoría"),
 *             @OA\Property(property="quantity", type="number", example=10),
 *             @OA\Property(property="unit_price", type="number", example=50000),
 *             @OA\Property(property="subtotal", type="number", example=500000)
 *         )
 *     ),
 *     @OA\Property(property="tax_rate", type="number", format="float", example=19.00, description="Tasa de impuesto (%)"),
 *     @OA\Property(property="notes", type="string", example="Notas adicionales", nullable=true),
 *     @OA\Property(property="terms", type="string", example="Términos y condiciones", nullable=true),
 *     @OA\Property(property="status", type="string", enum={"draft", "sent", "accepted", "rejected", "expired"}, example="draft", nullable=true)
 * )
 */
class SwaggerController extends Controller
{
    //
}
