<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Admin\PlatformDashboardController;
use App\Http\Controllers\ApiDocumentationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function (\Illuminate\Http\Request $request) {
    if ($request->user()) {
        $user = $request->user();
        $allowedPlatformEmails = collect(explode(',', (string) env('PLATFORM_ADMIN_EMAILS', '')))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->values();
        $isPlatformAdmin = $user->isPlatformAdmin()
            || $allowedPlatformEmails->contains(fn (string $email) => strcasecmp($email, (string) $user->email) === 0);

        return $isPlatformAdmin
            ? redirect()->route('platform-admin.dashboard')
            : redirect()->route('dashboard');
    }

    return Inertia::render('Landing', [
        'canRegister' => Features::enabled(Features::registration()),
        'canLogin' => true,
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('platform-admin', [PlatformDashboardController::class, 'index'])
        ->middleware('platform.admin')
        ->name('platform-admin.dashboard');
});

Route::middleware(['auth', 'verified', 'crm.access'])->group(function () {
    Route::get('/api/documentation', [ApiDocumentationController::class, 'show'])
        ->middleware('can:manage_api_tokens')
        ->name('api.documentation');
    Route::get('/api/documentation/postman', [ApiDocumentationController::class, 'downloadPostmanCollection'])
        ->middleware('can:manage_api_tokens')
        ->name('api.documentation.postman');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('can:view_leads')
        ->name('dashboard');
    Route::get('calendar', [CalendarController::class, 'index'])
        ->middleware('can:view_leads')
        ->name('calendar.index');
    
    Route::get('leads/kanban', [LeadController::class, 'kanban'])
        ->middleware('can:view_leads')
        ->name('leads.kanban');
    Route::patch('leads/{lead}/status', [LeadController::class, 'updateStatus'])
        ->middleware('can:edit_leads')
        ->name('leads.update-status');
    Route::get('leads', [LeadController::class, 'index'])->middleware('can:view_leads')->name('leads.index');
    Route::post('leads', [LeadController::class, 'store'])->middleware('can:create_leads')->name('leads.store');
    Route::match(['put', 'patch'], 'leads/{lead}', [LeadController::class, 'update'])->middleware('can:edit_leads')->name('leads.update');
    Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->middleware('can:delete_leads')->name('leads.destroy');
    
    Route::resource('companies', CompanyController::class);
    Route::resource('quotations', QuotationController::class);
    Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'pdf'])->name('quotations.pdf');
    Route::patch('quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');
    Route::resource('users', UserController::class)->except(['show'])->middleware('can:manage_users');
    Route::resource('roles', RoleController::class)->except(['show'])->middleware('can:manage_roles');
    Route::resource('permissions', PermissionController::class)->except(['show'])->middleware('can:manage_permissions');
    Route::resource('lead-statuses', LeadStatusController::class)->except(['show'])->middleware('can:manage_lead_statuses');
});

require __DIR__.'/settings.php';
