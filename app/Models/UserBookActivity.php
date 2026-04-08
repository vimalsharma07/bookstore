<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBookActivity extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'action',
        'weight',
        'occurred_at',
    ];

    protected $casts = [
        'weight' => 'integer',
        'occurred_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
