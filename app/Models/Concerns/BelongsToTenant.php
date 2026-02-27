<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenantId = auth()->user()?->tenant_id;

            if ($tenantId) {
                $builder->where($builder->getModel()->getTable().'.tenant_id', $tenantId);
            }
        });

        static::creating(function ($model) {
            if (! $model->tenant_id && auth()->check()) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }
}
