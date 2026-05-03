<x-layouts.store title="Complete subscription payment">
    <div class="max-w-lg mx-auto">
        <h1 class="font-display text-3xl">Payment pending</h1>
        <p class="mt-2 text-ink-500 dark:text-gray-300 text-sm">
            Complete your Razorpay payment to activate your reading subscription.
        </p>

        @if (session('status'))
            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 dark:bg-amber-950/30 dark:border-amber-800 px-4 py-3 text-sm text-amber-950 dark:text-amber-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 rounded-2xl border border-black/8 dark:border-white/10 bg-white/75 dark:bg-white/[0.06] p-6 space-y-2 text-sm">
            <div class="flex justify-between gap-4">
                <span class="text-ink-500 dark:text-gray-400">Plan</span>
                <span class="font-medium">{{ $subscription->plan_key === 'custom' ? $subscription->custom_days.' days (custom)' : $subscription->plan_key }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-ink-500 dark:text-gray-400">Amount</span>
                <span class="font-semibold tabular-nums">{{ strtoupper($subscription->currency) }} {{ number_format($subscription->price_cents / 100, 2) }}</span>
            </div>
        </div>

        @if($razorpayConfigured)
            <form method="POST" action="{{ route('subscriptions.razorpay.start', $subscription) }}" class="mt-8">
                @csrf
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-ink-900 text-white font-medium hover:bg-black transition">
                    Pay with Razorpay
                </button>
            </form>
        @else
            <p class="mt-6 text-sm text-rose-600 dark:text-rose-300">Razorpay is not configured on this server.</p>
        @endif

        <a href="{{ route('subscriptions.index') }}" class="mt-4 block text-center text-sm text-ink-500 hover:underline">Back to plans</a>
    </div>
</x-layouts.store>
