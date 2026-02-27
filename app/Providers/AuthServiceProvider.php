<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->isJefatura() ? true : null;
        });

        try {
            Permission::query()
                ->select('name')
                ->distinct()
                ->pluck('name')
                ->each(function ($permission) {
                    Gate::define($permission, function ($user) use ($permission) {
                        return $user->hasPermission($permission);
                    });
                });
        } catch (Throwable $exception) {
            // During image build / package discovery the DB may be unavailable.
            // Permissions will be loaded normally when the app boots with DB access.
        }
    }
}
