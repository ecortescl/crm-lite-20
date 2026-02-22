<?php

use App\Http\Controllers\Settings\ApiTokenController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\PlatformController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('user-password.edit');

    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance.edit');

    Route::get('settings/platform', [PlatformController::class, 'edit'])
        ->middleware('can:manage_platform_settings')
        ->name('platform.edit');
    Route::match(['post', 'patch'], 'settings/platform', [PlatformController::class, 'update'])
        ->middleware('can:manage_platform_settings')
        ->name('platform.update');
    Route::delete('settings/platform/logo', [PlatformController::class, 'deleteLogo'])
        ->middleware('can:manage_platform_settings')
        ->name('platform.logo.delete');

    Route::get('settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');

    // API Tokens
    Route::get('settings/api-tokens', [ApiTokenController::class, 'index'])
        ->middleware('can:manage_api_tokens')
        ->name('api-tokens.index');
    Route::post('settings/api-tokens', [ApiTokenController::class, 'store'])
        ->middleware('can:manage_api_tokens')
        ->name('api-tokens.store');
    Route::delete('settings/api-tokens/{token}', [ApiTokenController::class, 'destroy'])
        ->middleware('can:manage_api_tokens')
        ->name('api-tokens.destroy');
});
