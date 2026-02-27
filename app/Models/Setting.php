<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'key', 'value'];

    public static function get($key, $default = null, ?int $tenantId = null)
    {
        $resolvedTenantId = $tenantId ?? auth()->user()?->tenant_id;
        $cacheTenantPart = $resolvedTenantId ?? 'public';

        return Cache::rememberForever("setting.{$cacheTenantPart}.{$key}", function () use ($key, $default, $resolvedTenantId) {
            $setting = static::withoutGlobalScope('tenant')
                ->where('tenant_id', $resolvedTenantId)
                ->where('key', $key)
                ->first();

            return $setting ? $setting->value : $default;
        });
    }

    public static function set($key, $value, ?int $tenantId = null)
    {
        $resolvedTenantId = $tenantId ?? auth()->user()?->tenant_id;

        if (! $resolvedTenantId) {
            return null;
        }

        $setting = static::withoutGlobalScope('tenant')->updateOrCreate(
            ['tenant_id' => $resolvedTenantId, 'key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting.{$resolvedTenantId}.{$key}");

        return $setting;
    }
}
