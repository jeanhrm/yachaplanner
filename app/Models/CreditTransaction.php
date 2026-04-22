<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends Model
{
    protected $fillable = [
        'user_id', 'institution_id', 'type',
        'amount', 'balance_after', 'description',
        'reference_id', 'tokens_used',
    ];

    protected $casts = [
        'amount'       => 'integer',
        'balance_after'=> 'integer',
        'tokens_used'  => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}