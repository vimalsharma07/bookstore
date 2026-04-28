<x-layouts.store title="Checkout — Razorpay">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#3395FF]/15 dark:bg-[#3395FF]/20 px-3 py-1 text-xs font-medium text-[#0F4C81] dark:text-[#9EC9FF]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.18l6.9 3.45L12 11.09 5.1 7.63 12 4.18zM4 8.82l7 3.5v7.36l-7-3.5V8.82zm9 11.36v-7.36l7-3.5v7.36l-7 3.5z"/></svg>
                Razorpay checkout
            </div>
            <h1 class="mt-3 font-display text-3xl sm:text-4xl tracking-tight">Complete your payment</h1>
            <p class="mt-2 text-ink-500 dark:text-gray-300 max-w-2xl">
                Pay securely with Razorpay. After payment you’ll return here and we’ll add your books to <strong class="text-ink-900 dark:text-white">My Library</strong> automatically.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-900 dark:text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 space-y-6">
                @if($order->status === 'paid')
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-900 dark:text-emerald-100">
                        Payment confirmed. Your books are in <a href="{{ route('library.index') }}" class="underline font-medium">My Library</a>.
                    </div>
                @elseif(!$razorpayConfigured)
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 dark:bg-amber-950/25 dark:border-amber-800 p-6 text-sm text-amber-950 dark:text-amber-100">
                        <div class="font-display text-lg text-amber-950 dark:text-amber-50">Payments not configured</div>
                        <p class="mt-2 opacity-90">Set <span class="font-mono text-xs">RAZORPAY_KEY</span> and <span class="font-mono text-xs">RAZORPAY_SECRET</span> in your environment and ensure <span class="font-mono text-xs">APP_URL</span> matches this site (used for the Razorpay return URL).</p>
                    </div>
                @else
                    <div class="rounded-3xl border border-[#3395FF]/30 bg-gradient-to-br from-[#3395FF]/10 to-white dark:from-[#3395FF]/15 dark:to-white/5 p-6 sm:p-8 shadow-soft">
                        <div class="font-display text-xl text-ink-900 dark:text-white">Pay with Razorpay</div>
                        <p class="mt-2 text-sm text-ink-600 dark:text-gray-300">
                            You’ll open Razorpay’s secure payment page. Use card, UPI, or other methods enabled on your Razorpay account.
                        </p>

                        <form method="POST" action="{{ route('checkout.razorpay.start', $order) }}" class="mt-6">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-[#3395FF] text-white font-semibold hover:bg-[#2674CC] transition shadow-lg shadow-[#3395FF]/25">
                                Pay {{ strtoupper($order->currency) }} {{ number_format($order->total_cents / 100, 2) }}
                            </button>
                        </form>

                        <p class="mt-4 text-xs text-ink-500 dark:text-gray-400">
                            If you already paid, use the button again to open the receipt page, or wait a moment and refresh after returning from Razorpay.
                        </p>
                    </div>
                @endif

                @if($order->status === 'payment_submitted' && $order->payment_proof_path)
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:bg-amber-950/30 dark:border-amber-800 px-4 py-3 text-sm text-amber-900 dark:text-amber-100">
                        We previously received a manual payment screenshot for this order. Razorpay payment will override once completed.
                    </div>
                @endif
            </div>

            <aside class="lg:col-span-5">
                <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6 shadow-soft lg:sticky lg:top-24">
                    <div class="font-display text-2xl">Order summary</div>
                    <div class="mt-4 space-y-3 text-sm">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-ink-700 dark:text-gray-200">{{ $item->title_snapshot }} <span class="text-ink-500 dark:text-gray-300">×{{ $item->quantity }}</span></div>
                                <div class="text-ink-500 dark:text-gray-300">
                                    {{ strtoupper($order->currency) }} {{ number_format(($item->unit_price_cents * $item->quantity)/100, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-4 border-t border-black/10 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <span class="text-ink-500 dark:text-gray-300">Total due</span>
                            <span class="font-semibold text-lg">{{ strtoupper($order->currency) }} {{ number_format($order->total_cents / 100, 2) }}</span>
                        </div>
                        <div class="mt-2 text-xs text-ink-500 dark:text-gray-300">
                            Order #{{ $order->id }} · Status: <span class="font-medium">{{ $order->status }}</span>
                        </div>
                        @if($order->razorpay_payment_link_id)
                            <div class="mt-2 text-xs font-mono text-ink-500 dark:text-gray-400 break-all">
                                Razorpay link: {{ $order->razorpay_payment_link_id }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('library.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">My Library</a>
                        <a href="{{ route('books.index') }}" class="px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">Browse</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.store>
