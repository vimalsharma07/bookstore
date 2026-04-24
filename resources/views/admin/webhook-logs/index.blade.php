<x-admin.layout title="Admin · Webhook Logs">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Webhook Logs</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Incoming Razorpay webhooks and forward status.</p>
        </div>
    </div>

    <div class="mt-6 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-black/5 dark:bg-white/10 text-ink-700 dark:text-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3">#</th>
                        <th class="text-left px-4 py-3">Event</th>
                        <th class="text-left px-4 py-3">Signature</th>
                        <th class="text-left px-4 py-3">Forward URL</th>
                        <th class="text-left px-4 py-3">Forward Status</th>
                        <th class="text-left px-4 py-3">Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-t border-black/5 dark:border-white/10">
                            <td class="px-4 py-3 font-medium">{{ $log->id }}</td>
                            <td class="px-4 py-3">{{ $log->event ?? 'unknown' }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $log->signature ? \Illuminate\Support\Str::limit($log->signature, 28) : '—' }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $log->forwarded_to ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full border {{ $log->is_forward_success ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/30' : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-500/30' }}">
                                    {{ $log->forward_status_code ?? 'failed' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $log->created_at?->format('M j, Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr class="border-t border-black/5 dark:border-white/10">
                            <td class="px-4 py-4 text-ink-500 dark:text-gray-300" colspan="6">No webhook logs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>
</x-admin.layout>
