<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadStatus extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'color',
        'icon',
        'order',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
