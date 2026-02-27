<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique('companies_rut_unique');
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['tenant_id', 'rut']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropUnique('quotations_quotation_number_unique');
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['tenant_id', 'quotation_number']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_unique');
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['tenant_id', 'name']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique('permissions_name_unique');
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['tenant_id', 'name']);
        });

        Schema::table('lead_statuses', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique('settings_key_unique');
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['tenant_id', 'key']);
        });

        $tenantName = config('app.name', 'CRM Lite');
        $tenantSlug = Str::slug($tenantName).'-legacy';
        $baseSlug = $tenantSlug;
        $counter = 1;

        while (DB::table('tenants')->where('slug', $tenantSlug)->exists()) {
            $tenantSlug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $tenantId = DB::table('tenants')->insertGetId([
            'name' => $tenantName.' Legacy',
            'slug' => $tenantSlug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (['users', 'companies', 'leads', 'quotations', 'roles', 'permissions', 'lead_statuses', 'settings'] as $table) {
            DB::table($table)->whereNull('tenant_id')->update(['tenant_id' => $tenantId]);
        }
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique('settings_tenant_id_key_unique');
            $table->dropConstrainedForeignId('tenant_id');
            $table->unique('key');
        });

        Schema::table('lead_statuses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique('permissions_tenant_id_name_unique');
            $table->dropConstrainedForeignId('tenant_id');
            $table->unique('name');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_tenant_id_name_unique');
            $table->dropConstrainedForeignId('tenant_id');
            $table->unique('name');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropUnique('quotations_tenant_id_quotation_number_unique');
            $table->dropConstrainedForeignId('tenant_id');
            $table->unique('quotation_number');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique('companies_tenant_id_rut_unique');
            $table->dropConstrainedForeignId('tenant_id');
            $table->unique('rut');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::dropIfExists('tenants');
    }
};
