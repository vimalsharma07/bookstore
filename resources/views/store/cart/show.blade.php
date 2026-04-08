<x-layouts.store title="Your cart">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Your cart</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Review items before checkout.</p>
        </div>
        <a href="{{ route('books.index') }}" class="text-sm px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Continue shopping</a>
    </div>

    @if($lines->isEmpty())
        <div class="mt-6 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            Your cart is empty. Browse books and add a few favorites.
        </div>
    @else
        <div class="mt-6 grid lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 space-y-4">
                <form id="cart-update-form" method="POST" action="{{ route('cart.update') }}" class="space-y-4">
                    @csrf
                @foreach($lines as $line)
                    @php($b = $line['book'])
                    <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 overflow-hidden">
                        <div class="flex">
                            <div class="w-28 sm:w-32 aspect-[3/4] shrink-0">
                                <a href="{{ route('books.show', $b) }}" class="block h-full w-full">
                                    <x-book-cover :book="$b" size="sm" class="rounded-none" />
                                </a>
                            </div>
                            <div class="p-4 flex-1">
                                <a href="{{ route('books.show', $b) }}" class="font-semibold text-lg hover:underline">{{ $b->title }}</a>
                                <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="text-sm font-medium">{{ $b->display_price }}</div>
                                    <div class="text-xs text-ink-500 dark:text-gray-300">Subtotal: {{ strtoupper($currency) }} {{ number_format($line['subtotal_cents']/100, 2) }}</div>
                                </div>

                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    <label class="text-sm text-ink-500 dark:text-gray-300">Qty</label>
                                    <input type="number" min="0" max="10"
                                           name="qty[{{ $b->id }}]"
                                           value="{{ $line['qty'] }}"
                                           class="w-24 px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />

                                    <form method="POST" action="{{ route('cart.remove', $b) }}">
                                        @csrf
                                        <button class="px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </form>
            </div>

            <div class="lg:col-span-4">
                <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6 sticky top-24">
                    <div class="font-display text-2xl">Summary</div>
                    <div class="mt-4 flex items-center justify-between text-sm">
                        <span class="text-ink-500 dark:text-gray-300">Total</span>
                        <span class="font-medium">{{ strtoupper($currency) }} {{ number_format($totalCents/100, 2) }}</span>
                    </div>

                    <div class="mt-5 space-y-2">
                        <button type="submit" form="cart-update-form" class="w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                            Update cart
                        </button>

                        @auth
                            <form method="POST" action="{{ route('checkout.cart') }}">
                                @csrf
                                <button class="w-full px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                                    Proceed to checkout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-flex justify-center px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                                Log in to checkout
                            </a>
                        @endauth

                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            <button class="w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                                Clear cart
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 text-xs text-ink-500 dark:text-gray-300">
                        Payment integration is currently a placeholder. You can plug your own gateway into the checkout flow.
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layouts.store>

