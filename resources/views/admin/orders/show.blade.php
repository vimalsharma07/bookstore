<x-admin.layout title="Admin · Order #{{ $order->id }}">
    <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
            <h1 class="font-display text-3xl">Order #{{ $order->id }}</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Status: <span class="font-medium">{{ $order->status }}</span></p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">Back</a>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-900 dark:text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

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

            @if($order->payment_proof_path)
                <div class="mt-8">
                    <h3 class="font-display text-lg">Payment screenshot</h3>
                    <p class="text-sm text-ink-500 dark:text-gray-300 mt-1">
                        Uploaded {{ $order->payment_proof_submitted_at?->format('M j, Y H:i') ?? '—' }}
                    </p>
                    <a href="{{ asset($order->payment_proof_path) }}" target="_blank" rel="noopener" class="mt-3 inline-block rounded-2xl border border-black/10 dark:border-white/15 overflow-hidden max-w-md">
                        <img src="{{ asset($order->payment_proof_path) }}" alt="Payment proof" class="w-full h-auto object-contain bg-black/5 dark:bg-white/5" />
                    </a>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl">PayPal (manual)</h2>
                <div class="mt-3 text-sm text-ink-500 dark:text-gray-300 space-y-2">
                    <div><span class="text-ink-700 dark:text-gray-200">Pay to:</span> <span class="font-mono break-all">{{ $paymentEmail }}</span></div>
                    <div><span class="text-ink-700 dark:text-gray-200">Total:</span> {{ strtoupper($order->currency) }} {{ number_format($order->total_cents / 100, 2) }}</div>
                    <div><span class="text-ink-700 dark:text-gray-200">Paid at:</span> {{ $order->paid_at?->format('M j, Y H:i') ?? '—' }}</div>
                    <div><span class="text-ink-700 dark:text-gray-200">Customer email:</span> {{ $order->email ?? $order->user?->email ?? '—' }}</div>
                </div>
                @if($order->stripe_session_id || $order->stripe_payment_intent_id)
                    <div class="mt-4 pt-4 border-t border-black/10 dark:border-white/10 text-xs text-ink-500 space-y-1">
                        @if($order->stripe_session_id)
                            <div><span class="text-ink-700 dark:text-gray-200">Stripe session:</span> <span class="font-mono">{{ $order->stripe_session_id }}</span></div>
                        @endif
                        @if($order->stripe_payment_intent_id)
                            <div><span class="text-ink-700 dark:text-gray-200">Payment intent:</span> <span class="font-mono">{{ $order->stripe_payment_intent_id }}</span></div>
                        @endif
                    </div>
                @endif
            </div>

            @if($order->status !== 'paid')
                <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                    <h2 class="font-display text-xl">Confirm payment</h2>
                    <p class="mt-2 text-sm text-ink-500 dark:text-gray-300">
                        After you verify the PayPal payment matches this order, mark it paid to add books to the customer’s library.
                    </p>
                    @if($order->status === 'pending' && ! $order->payment_proof_path)
                        <p class="mt-3 text-sm text-amber-800 dark:text-amber-200 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 px-3 py-2">
                            No screenshot yet—wait for the customer to upload proof, or confirm only if you verified payment elsewhere.
                        </p>
                    @endif
                    <form method="POST" action="{{ route('admin.orders.confirm-payment', $order) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition text-sm font-medium">
                            Mark as paid &amp; fulfill order
                        </button>
                    </form>
                </div>
            @else
                <div class="rounded-3xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50/80 dark:bg-emerald-950/30 p-6 text-sm text-emerald-900 dark:text-emerald-100">
                    This order is paid and library items have been granted.
                </div>
            @endif
        </div>
    </div>
</x-admin.layout>
