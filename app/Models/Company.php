<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'business_name',
        'rut',
        'fantasy_name',
        'giro',
        'email',
        'phone',
        'website',
        'address',
        'commune',
        'city',
        'region',
        'notes',
        'size',
        'industry',
    ];

    protected $appends = [
        'display_name',
        'formatted_rut',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Formatea el RUT con puntos y guión
     */
    public function getFormattedRutAttribute(): string
    {
        if (!$this->rut) {
            return '';
        }

        $rut = preg_replace('/[^0-9kK]/', '', $this->rut);
        $dv = substr($rut, -1);
        $number = substr($rut, 0, -1);
        
        return number_format($number, 0, '', '.') . '-' . $dv;
    }

    /**
     * Obtiene el nombre para mostrar (fantasía o razón social)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->fantasy_name ?: $this->business_name;
    }
}
