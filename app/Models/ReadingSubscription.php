<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReadingSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_key',
        'status',
        'price_cents',
        'currency',
        'custom_days',
        'max_books',
        'starts_at',
        'ends_at',
        'paid_at',
        'razorpay_payment_link_id',
    ];

    protected $casts = [
        'custom_days' => 'integer',
        'max_books' => 'integer',
        'price_cents' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'reading_subscription_book')->withTimestamps();
    }

    public function isUnlimited(): bool
    {
        return $this->max_books === null;
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->ends_at
            && $this->ends_at->isFuture();
    }
}
