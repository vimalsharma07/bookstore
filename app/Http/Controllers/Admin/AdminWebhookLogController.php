<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class AdminWebhookLogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $logs = WebhookLog::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('request_payload', 'like', '%'.$search.'%');
            })
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.webhook-logs.index', compact('logs', 'search'));
    }
}
