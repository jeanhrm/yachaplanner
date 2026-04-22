<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Subscription extends Model
{
    protected $fillable = [
        'plan_id', 'subscriber_type', 'subscriber_id',
        'culqi_charge_id', 'culqi_customer_id', 'amount_paid_soles',
        'status', 'starts_at', 'ends_at', 'cancelled_at', 'cancel_reason',
        'invoice_ruc', 'invoice_razon_social', 'invoice_address', 'invoice_number',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }
}