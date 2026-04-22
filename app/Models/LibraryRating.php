<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryRating extends Model
{
    protected $fillable = [
        'library_item_id', 'user_id',
        'rating', 'comment', 'is_verified_use',
    ];

    protected $casts = [
        'is_verified_use' => 'boolean',
    ];

    public function libraryItem(): BelongsTo
    {
        return $this->belongsTo(LibraryItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}