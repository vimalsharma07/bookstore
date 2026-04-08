<x-admin.layout title="Admin · Orders">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Orders</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Track payments and fulfillment.</p>
        </div>
    </div>

    <div class="mt-6 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-black/5 dark:bg-white/10 text-ink-700 dark:text-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3">#</th>
                        <th class="text-left px-4 py-3">User</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Total</th>
                        <th class="text-left px-4 py-3">Paid</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                        <tr class="border-t border-black/5 dark:border-white/10">
                            <td class="px-4 py-3 font-medium">{{ $o->id }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">
                                {{ $o->user?->email ?? $o->email ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-white/60 dark:bg-white/10 border border-black/5 dark:border-white/10">
                                    {{ $o->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ strtoupper($o->currency) }} {{ number_format($o->total_cents / 100, 2) }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $o->paid_at?->format('M j, Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $o) }}" class="px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
</x-admin.layout>

