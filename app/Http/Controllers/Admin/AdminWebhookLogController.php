<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebhookLog;

class AdminWebhookLogController extends Controller
{
    public function index()
    {
        $logs = WebhookLog::query()
            ->latest()
            ->paginate(30);

        return view('admin.webhook-logs.index', compact('logs'));
    }
}
