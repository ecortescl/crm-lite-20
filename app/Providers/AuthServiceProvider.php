<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->isJefatura() ? true : null;
        });

        Permission::query()
            ->pluck('name')
            ->each(function ($permission) {
                Gate::define($permission, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            });
    }
}
