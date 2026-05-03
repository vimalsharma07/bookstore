<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'currency',
        'total_cents',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'razorpay_payment_link_id',
        'razorpay_order_id',
        'email',
        'billing_details',
        'payment_proof_path',
        'payment_proof_submitted_at',
        'paid_at',
    ];

    protected $casts = [
        'billing_details' => 'array',
        'paid_at' => 'datetime',
        'payment_proof_submitted_at' => 'datetime',
        'total_cents' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
