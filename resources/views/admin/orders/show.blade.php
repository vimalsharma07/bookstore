<x-admin.layout title="Admin · Order #{{ $order->id }}">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Order #{{ $order->id }}</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Status: <span class="font-medium">{{ $order->status }}</span></p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">Back</a>
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <h2 class="font-display text-xl">Items</h2>
            <div class="mt-4 space-y-3">
                @foreach($order->items as $item)
                    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-medium">{{ $item->title_snapshot }}</div>
                            <div class="text-sm text-ink-500 dark:text-gray-300">{{ $item->book?->author ?? '' }}</div>
                        </div>
                        <div class="text-sm">
                            {{ strtoupper($order->currency) }} {{ number_format(($item->unit_price_cents * $item->quantity) / 100, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <h2 class="font-display text-xl">Payment</h2>
            <div class="mt-3 text-sm text-ink-500 dark:text-gray-300 space-y-2">
                <div><span class="text-ink-700 dark:text-gray-200">Total:</span> {{ strtoupper($order->currency) }} {{ number_format($order->total_cents / 100, 2) }}</div>
                <div><span class="text-ink-700 dark:text-gray-200">Paid at:</span> {{ $order->paid_at?->format('M j, Y H:i') ?? '—' }}</div>
                <div><span class="text-ink-700 dark:text-gray-200">Stripe session:</span> <span class="font-mono text-xs">{{ $order->stripe_session_id ?? '—' }}</span></div>
                <div><span class="text-ink-700 dark:text-gray-200">Payment intent:</span> <span class="font-mono text-xs">{{ $order->stripe_payment_intent_id ?? '—' }}</span></div>
                <div><span class="text-ink-700 dark:text-gray-200">Email:</span> {{ $order->email ?? $order->user?->email ?? '—' }}</div>
            </div>
        </div>
    </div>
</x-admin.layout>

