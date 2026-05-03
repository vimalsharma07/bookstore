<x-layouts.store title="Reading subscriptions">
    <div class="max-w-5xl mx-auto">
        <div class="text-center max-w-2xl mx-auto">
            <h1 class="font-display text-3xl sm:text-4xl tracking-tight">Read more for less</h1>
            <p class="mt-2 text-ink-500 dark:text-gray-300 text-sm sm:text-base">
                Choose a pass for unlimited reading for a set period, or a flexible day-based plan (pick up to {{ $custom['max_books'] }} books).
                Prices in {{ $currency }}.
            </p>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-900 dark:text-emerald-100 text-center">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-10 grid md:grid-cols-3 gap-6">
            @foreach ($plans as $key => $plan)
                <div class="rounded-2xl border border-black/8 dark:border-white/10 bg-white/75 dark:bg-white/[0.06] p-6 flex flex-col shadow-sm">
                    <div class="font-display text-xl">{{ $plan['label'] }}</div>
                    <div class="mt-3 font-display text-3xl tabular-nums">{{ $currency }} {{ number_format($plan['price_cents'] / 100, 0) }}</div>
                    <p class="mt-2 text-sm text-ink-500 dark:text-gray-400 flex-1">Unlimited catalog access for the whole period.</p>
                    @auth
                        <form method="POST" action="{{ route('subscriptions.store') }}" class="mt-6">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $key }}" />
                            <button type="submit" class="w-full py-3 rounded-xl bg-ink-900 text-white text-sm font-medium hover:bg-black transition">
                                Subscribe
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="mt-6 block text-center w-full py-3 rounded-xl border border-black/12 dark:border-white/15 text-sm font-medium hover:bg-black/5 dark:hover:bg-white/10 transition">
                            Log in to subscribe
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>

        <div class="mt-10 rounded-2xl border border-black/8 dark:border-white/10 bg-gradient-to-br from-amber-50/80 to-white dark:from-amber-950/20 dark:to-white/[0.04] p-6 sm:p-8">
            <h2 class="font-display text-2xl">Custom · ${{ number_format($custom['price_per_day_cents'] / 100, 0) }} per day</h2>
            <p class="mt-2 text-sm text-ink-600 dark:text-gray-300">
                Choose how many days you need ({{ $custom['min_days'] }}–{{ $custom['max_days'] }}). During your pass you can add up to <strong>{{ $custom['max_books'] }}</strong> books from our catalog to read—swap titles anytime from each book’s page.
            </p>
            @auth
                <form method="POST" action="{{ route('subscriptions.store') }}" class="mt-6 flex flex-col sm:flex-row gap-4 sm:items-end">
                    @csrf
                    <input type="hidden" name="plan" value="custom" />
                    <div class="flex-1">
                        <label for="custom_days" class="text-xs font-medium text-ink-600 dark:text-gray-300">Number of days</label>
                        <input type="number" name="custom_days" id="custom_days" min="{{ $custom['min_days'] }}" max="{{ $custom['max_days'] }}" value="14" required
                               class="mt-1.5 w-full sm:max-w-[12rem] px-4 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white dark:bg-white/5 text-sm" />
                    </div>
                    <div class="text-sm text-ink-600 dark:text-gray-400 pb-2 tabular-nums" id="custom-total" aria-live="polite"></div>
                    <button type="submit" class="shrink-0 px-6 py-3 rounded-xl bg-ink-900 text-white text-sm font-medium hover:bg-black transition">
                        Continue to pay
                    </button>
                </form>
            @else
                <div class="mt-6">
                    <a href="{{ route('login') }}" class="inline-flex px-6 py-3 rounded-xl bg-ink-900 text-white text-sm font-medium hover:bg-black transition">Log in to choose days</a>
                </div>
            @endauth
        </div>

        @auth
            <p class="mt-8 text-center text-xs text-ink-500 dark:text-gray-400">
                Secured by Razorpay. After payment, fixed plans unlock the full catalog immediately. Custom plans let you pick books from each title’s page (up to {{ $custom['max_books'] }} at a time).
            </p>
        @endauth
    </div>

    @auth
        <script>
            (function () {
                var input = document.getElementById('custom_days');
                var out = document.getElementById('custom-total');
                if (!input || !out) return;
                var rate = {{ (int) $custom['price_per_day_cents'] }} / 100;
                function fmt() {
                    var d = parseInt(input.value, 10) || 0;
                    var t = (d * rate).toFixed(2);
                    out.textContent = 'Total: {{ $currency }} ' + t + ' · {{ $currency }} ' + rate.toFixed(2) + '/day';
                }
                input.addEventListener('input', fmt);
                fmt();
            })();
        </script>
    @endauth
</x-layouts.store>
