<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_company',
        'company_id',
        'notes',
        'lead_status_id',
        'assigned_to',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'scheduled_at',
        'meeting_notes',
        'budget',
        'quote_items',
        'invoice_number',
        'final_amount',
        'closed_at',
        'payment_status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'closed_at' => 'datetime',
        'budget' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'quote_items' => 'array',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Obtiene el nombre de la empresa (de la relación o del campo de texto)
     */
    public function getCompanyNameAttribute(): ?string
    {
        return $this->company?->display_name ?? $this->contact_company;
    }
}
