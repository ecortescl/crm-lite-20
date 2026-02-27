<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'quotation_number',
        'lead_id',
        'company_id',
        'user_id',
        'client_name',
        'client_rut',
        'client_email',
        'client_phone',
        'client_address',
        'issue_date',
        'valid_until',
        'items',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'notes',
        'terms',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'issue_date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Genera el siguiente número de cotización
     */
    public static function generateQuotationNumber(): string
    {
        $year = date('Y');
        $lastQuotation = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastQuotation) {
            $lastNumber = (int) substr($lastQuotation->quotation_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'COT-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcula los totales basados en los items
     */
    public function calculateTotals(): void
    {
        $subtotal = 0;

        foreach ($this->items as $item) {
            $subtotal += $item['subtotal'];
        }

        $this->subtotal = $subtotal;
        $this->tax_amount = $subtotal * ($this->tax_rate / 100);
        $this->total = $subtotal + $this->tax_amount;
    }
}
