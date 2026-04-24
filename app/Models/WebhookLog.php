<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = [
        'source',
        'event',
        'signature',
        'request_headers',
        'request_payload',
        'forwarded_to',
        'forward_status_code',
        'forward_response_body',
        'is_forward_success',
        'forwarded_at',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'is_forward_success' => 'boolean',
        'forwarded_at' => 'datetime',
    ];
}
