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
    Route::apiResource('companies', CompanyApiController::class)->names([
        'index' => 'api.companies.index',
        'store' => 'api.companies.store',
        'show' => 'api.companies.show',
        'update' => 'api.companies.update',
        'destroy' => 'api.companies.destroy',
    ]);
    
    // Leads
    Route::apiResource('leads', LeadApiController::class)->names([
        'index' => 'api.leads.index',
        'store' => 'api.leads.store',
        'show' => 'api.leads.show',
        'update' => 'api.leads.update',
        'destroy' => 'api.leads.destroy',
    ]);
    Route::get('lead-statuses', [LeadApiController::class, 'statuses'])->name('api.lead-statuses');
    
});
