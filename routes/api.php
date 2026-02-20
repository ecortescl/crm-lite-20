<?php

use App\Http\Controllers\Api\CompanyApiController;
use App\Http\Controllers\Api\LeadApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas de API para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider y todas ellas serán
| asignadas al grupo de middleware "api". ¡Haz algo grandioso!
|
*/

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // Empresas
    Route::apiResource('companies', CompanyApiController::class);
    
    // Leads
    Route::apiResource('leads', LeadApiController::class);
    Route::get('lead-statuses', [LeadApiController::class, 'statuses']);
    
});
