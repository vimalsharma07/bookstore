<x-layouts.store title="Complete payment">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <div class="font-display text-4xl tracking-tight">Secure Checkout</div>
            <p class="mt-2 text-ink-500 dark:text-gray-300">
                Complete your order using the test card simulator.
            </p>
        </div>

        <div class="grid lg:grid-cols-12 gap-6">
            <section class="lg:col-span-7 space-y-6">
                <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6 shadow-soft">
                    <div class="font-display text-2xl">Payment details</div>
                    <p class="mt-1 text-sm text-ink-500 dark:text-gray-300">
                        Use one of the testing cards below.
                    </p>

                    <div class="mt-4 grid sm:grid-cols-3 gap-3 text-xs">
                        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-3">
                            <div class="font-medium text-ink-700 dark:text-gray-200">Success</div>
                            <div class="mt-1 font-mono">4242 4242 4242 4242</div>
                        </div>
                        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-3">
                            <div class="font-medium text-ink-700 dark:text-gray-200">Declined</div>
                            <div class="mt-1 font-mono">4000 0000 0000 0002</div>
                        </div>
                        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-3">
                            <div class="font-medium text-ink-700 dark:text-gray-200">Insufficient</div>
                            <div class="mt-1 font-mono">4000 0000 0000 9995</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('checkout.pay', $order) }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="text-sm text-ink-500 dark:text-gray-300">Card number</label>
                            <input name="card_number" placeholder="4242 4242 4242 4242"
                                   class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm text-ink-500 dark:text-gray-300">Expiry</label>
                                <input name="expiry" placeholder="12/34"
                                       class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                            </div>
                            <div>
                                <label class="text-sm text-ink-500 dark:text-gray-300">CVC</label>
                                <input name="cvc" placeholder="123"
                                       class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                            </div>
                        </div>

                        <button class="w-full px-5 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                            Pay {{ strtoupper($order->currency) }} {{ number_format($order->total_cents/100, 2) }}
                        </button>
                    </form>
                </div>
            </section>

            <aside class="lg:col-span-5">
                <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6 shadow-soft sticky top-24">
                    <div class="font-display text-2xl">Order Summary</div>
                    <div class="mt-4 space-y-3 text-sm">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-ink-700 dark:text-gray-200">{{ $item->title_snapshot }} <span class="text-ink-500 dark:text-gray-300">x{{ $item->quantity }}</span></div>
                                <div class="text-ink-500 dark:text-gray-300">
                                    {{ strtoupper($order->currency) }} {{ number_format(($item->unit_price_cents * $item->quantity)/100, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-4 border-t border-black/10 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <span class="text-ink-500 dark:text-gray-300">Total</span>
                            <span class="font-semibold">{{ strtoupper($order->currency) }} {{ number_format($order->total_cents / 100, 2) }}</span>
                        </div>
                        <div class="mt-2 text-xs text-ink-500 dark:text-gray-300">
                            Order #{{ $order->id }} · Status: {{ $order->status }}
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="{{ route('library.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">My Library</a>
                        <a href="{{ route('books.index') }}" class="px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">Back to browse</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.store>

