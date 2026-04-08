<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryItem extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'order_id',
        'purchased_at',
        'download_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'download_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
