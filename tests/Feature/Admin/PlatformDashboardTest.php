<?php

use App\Models\Tenant;
use App\Models\User;

test('platform admin can access global platform dashboard', function () {
    $tenant = Tenant::create([
        'name' => 'Tenant Demo',
        'slug' => 'tenant-demo',
    ]);

    $platformAdmin = User::factory()->create([
        'tenant_id' => $tenant->id,
        'is_platform_admin' => true,
    ]);

    $response = $this->actingAs($platformAdmin)->get(route('platform-admin.dashboard'));

    $response->assertOk();
});

test('non platform admin cannot access global platform dashboard', function () {
    $tenant = Tenant::create([
        'name' => 'Tenant Demo',
        'slug' => 'tenant-demo-2',
    ]);

    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
        'is_platform_admin' => false,
    ]);

    $response = $this->actingAs($user)->get(route('platform-admin.dashboard'));

    $response->assertForbidden();
});

test('platform admin cannot access tenant crm dashboard', function () {
    $tenant = Tenant::create([
        'name' => 'Tenant Demo',
        'slug' => 'tenant-demo-3',
    ]);

    $platformAdmin = User::factory()->create([
        'tenant_id' => $tenant->id,
        'is_platform_admin' => true,
    ]);

    $response = $this->actingAs($platformAdmin)->get(route('dashboard'));

    $response->assertForbidden();
});
