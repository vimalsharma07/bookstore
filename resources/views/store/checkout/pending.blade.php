<x-layouts.store title="Checkout — Pay with PayPal">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#0070ba]/10 dark:bg-[#0070ba]/20 px-3 py-1 text-xs font-medium text-[#0070ba]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.076 21.337H2.47a.641.641 0 01-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19a.987.987 0 00-.969.804l-.84 5.533-.003.02a.641.641 0 01-.632.74z"/><path d="M23.048 7.667c-.022-.15-.048-.296-.077-.437-.292-1.867.002-3.138 1.012-4.287C25.095 1.676 23.087 1.133 20.517 1.133h-7.46c-.524 0-.972.382-1.054.901L.837 20.597a.641.641 0 00.633.74h4.606a.987.987 0 00.969-.804l.931-6.118h2.19c4.298 0 7.664-1.747 8.647-6.797.03-.149.054-.294.077-.437z"/></svg>
                PayPal checkout
            </div>
            <h1 class="mt-3 font-display text-3xl sm:text-4xl tracking-tight">Send payment & confirm</h1>
            <p class="mt-2 text-ink-500 dark:text-gray-300 max-w-2xl">
                Pay the exact total below with PayPal to <strong class="text-ink-900 dark:text-white">{{ $paymentEmail }}</strong>, then upload a screenshot of the completed payment. We typically confirm within <strong>5 minutes</strong> during active hours.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-900 dark:text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 space-y-6">
                {{-- PayPal-style summary card --}}
                <div class="rounded-3xl border border-[#0070ba]/25 bg-gradient-to-br from-[#0070ba]/5 to-white dark:from-[#0070ba]/10 dark:to-white/5 p-6 sm:p-8 shadow-soft">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-sm text-ink-500 dark:text-gray-300">Pay to</div>
                            <div class="mt-1 font-mono text-lg font-semibold text-ink-900 dark:text-white break-all">{{ $paymentEmail }}</div>
                        </div>
                        <button type="button"
                                data-copy-email="{{ $paymentEmail }}"
                                class="js-copy-pay-email shrink-0 px-4 py-2 rounded-xl bg-[#0070ba] text-white text-sm font-medium hover:bg-[#005ea6] transition">
                            Copy email
                        </button>
                    </div>

                    <div class="mt-6 rounded-2xl bg-white/80 dark:bg-black/20 border border-black/5 dark:border-white/10 p-5">
                        <div class="text-sm text-ink-500 dark:text-gray-300">Amount to send (exactly)</div>
                        <div class="mt-2 text-4xl font-display font-semibold tracking-tight text-ink-900 dark:text-white">
                            {{ strtoupper($order->currency) }} {{ number_format($order->total_cents / 100, 2) }}
                        </div>
                        <p class="mt-2 text-xs text-ink-500 dark:text-gray-400">
                            Use “Friends & Family” or “Goods & Services” as you prefer—include the order number in the note if PayPal allows.
                        </p>
                    </div>

                    <ol class="mt-6 space-y-3 text-sm text-ink-600 dark:text-gray-300 list-decimal list-inside">
                        <li>Open the PayPal app or website and choose <strong>Send</strong>.</li>
                        <li>Enter <strong>{{ $paymentEmail }}</strong> as the recipient.</li>
                        <li>Enter the amount: <strong>{{ strtoupper($order->currency) }} {{ number_format($order->total_cents / 100, 2) }}</strong>.</li>
                        <li>Complete the payment, then take a screenshot showing the completed transaction.</li>
                        <li>Upload the screenshot below.</li>
                    </ol>
                </div>

                @if($order->status === 'paid')
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-900 dark:text-emerald-100">
                        Payment confirmed. Your books are in <a href="{{ route('library.index') }}" class="underline font-medium">My Library</a>.
                    </div>
                @elseif($order->status === 'payment_submitted')
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:bg-amber-950/30 dark:border-amber-800 px-4 py-3 text-sm text-amber-900 dark:text-amber-100">
                        We received your payment proof and will confirm shortly—usually within 5 minutes. You can leave this page; we’ll email you at {{ $order->email }} if anything is unclear.
                    </div>
                    @if($order->payment_proof_path)
                        <div class="text-xs text-ink-500 dark:text-gray-400">Proof uploaded {{ $order->payment_proof_submitted_at?->diffForHumans() ?? '' }}</div>
                    @endif
                @else
                    <form method="POST" action="{{ route('checkout.payment-proof', $order) }}" enctype="multipart/form-data" class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                        @csrf
                        <div class="font-display text-xl">Upload payment screenshot</div>
                        <p class="mt-1 text-sm text-ink-500 dark:text-gray-300">PNG, JPG, or WebP · max 5 MB</p>
                        <input type="file" name="payment_proof" required accept="image/jpeg,image/png,image/webp"
                               class="mt-4 block w-full text-sm text-ink-500 file:mr-4 file:rounded-xl file:border-0 file:bg-ink-900 file:px-4 file:py-2 file:text-white file:hover:bg-black" />
                        @error('payment_proof')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="mt-5 w-full sm:w-auto px-6 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition font-medium">
                            Submit proof
                        </button>
                    </form>
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
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('library.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">My Library</a>
                        <a href="{{ route('books.index') }}" class="px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">Browse</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        (function () {
            document.querySelectorAll('.js-copy-pay-email').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var email = btn.getAttribute('data-copy-email') || '';
                    if (!email) return;
                    navigator.clipboard.writeText(email).then(function () {
                        var t = btn.textContent;
                        btn.textContent = 'Copied!';
                        setTimeout(function () { btn.textContent = t; }, 2000);
                    });
                });
            });
        })();
    </script>
</x-layouts.store>
