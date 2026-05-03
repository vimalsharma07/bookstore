<?php

namespace App\Models;

use Carbon\Carbon;
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
        'razorpay_order_id',
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

    public function markPaidAndActivate(): void
    {
        $this->forceFill([
            'status' => 'active',
            'paid_at' => now(),
            'starts_at' => now(),
            'ends_at' => $this->computePaidThrough(),
        ])->save();
    }

    private function computePaidThrough(): Carbon
    {
        if ($this->plan_key === 'custom' && $this->custom_days) {
            return now()->addDays($this->custom_days);
        }

        $plan = config('reading_subscriptions.plans.'.$this->plan_key);
        $period = $plan['period'] ?? [];

        if (isset($period['months'])) {
            return now()->addMonths((int) $period['months']);
        }

        if (isset($period['years'])) {
            return now()->addYears((int) $period['years']);
        }

        return now()->addMonth();
    }
}

