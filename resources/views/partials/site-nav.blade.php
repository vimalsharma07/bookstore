<nav class="sticky top-0 z-40 bg-white/70 dark:bg-white/5 backdrop-blur border-b border-black/5 dark:border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-2xl bg-gradient-to-br from-amber-200 to-rose-200 dark:from-amber-400/20 dark:to-rose-400/20 border border-black/5 dark:border-white/10 shadow-soft"></div>
                <div class="leading-tight">
                    <div class="font-display text-lg tracking-tight">BookQueue</div>
                    <div class="text-xs text-ink-500 dark:text-gray-300 -mt-0.5">Read. Collect. Repeat.</div>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('books.index') }}" class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition text-sm">Browse</a>
                @auth
                    <a href="{{ route('library.index') }}" class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition text-sm">My Library</a>
                @endauth
                <a href="{{ url('/contact') }}" class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition text-sm">Contact</a>

                @php($shopCurrency = \App\Services\Currency::current())
                <form method="POST" action="{{ route('currency.update') }}" class="flex items-center">
                    @csrf
                    <label for="shop-currency" class="sr-only">Currency</label>
                    <select name="currency" id="shop-currency" onchange="this.form.submit()" class="text-sm rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 px-2.5 py-2 min-w-[5.5rem]">
                        <option value="USD" @selected($shopCurrency === 'USD')>USD</option>
                        <option value="EUR" @selected($shopCurrency === 'EUR')>EUR</option>
                        <option value="INR" @selected($shopCurrency === 'INR')>INR</option>
                    </select>
                </form>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('cart.show') }}" class="relative inline-flex items-center justify-center h-10 px-3 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">
                    <span>Cart</span>
                    @php($cartCount = \App\Services\Cart::count())
                    @if($cartCount > 0)
                        <span class="ml-2 inline-flex items-center justify-center min-w-6 h-6 px-2 rounded-full bg-ink-900 text-white text-xs">{{ $cartCount }}</span>
                    @endif
                </a>

                <button type="button"
                        onclick="window.__toggleTheme?.()"
                        class="inline-flex items-center justify-center h-10 w-10 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                    <span class="sr-only">Toggle theme</span>
                    <svg class="h-5 w-5 text-ink-700 dark:text-[#F3F2EE]" viewBox="0 0 24 24" fill="none">
                        <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 2v2m0 16v2M4 12H2m20 0h-2M5 5l1.5 1.5M17.5 17.5 19 19M19 5l-1.5 1.5M6.5 17.5 5 19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">Sign up</a>
                @endguest

                @auth
                    <div class="hidden sm:flex items-center gap-2">
                        <span class="text-sm text-ink-500 dark:text-gray-300">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition text-sm">Logout</button>
                        </form>
                    </div>
                @endauth

                <button type="button" data-nav-toggle class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path data-nav-icon="open" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/>
                        <path data-nav-icon="close" class="hidden" d="M6 18 18 6M6 6l12 12" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <div data-nav-panel class="hidden md:hidden pb-4">
            <div class="flex flex-col gap-2">
                @php($shopCurrency = \App\Services\Currency::current())
                <form method="POST" action="{{ route('currency.update') }}" class="px-3 py-2 flex items-center gap-2">
                    @csrf
                    <label for="shop-currency-mobile" class="text-sm text-ink-500 dark:text-gray-300">Currency</label>
                    <select name="currency" id="shop-currency-mobile" onchange="this.form.submit()" class="flex-1 text-sm rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 px-2.5 py-2">
                        <option value="USD" @selected($shopCurrency === 'USD')>USD</option>
                        <option value="EUR" @selected($shopCurrency === 'EUR')>EUR</option>
                        <option value="INR" @selected($shopCurrency === 'INR')>INR</option>
                    </select>
                </form>
                <a href="{{ route('books.index') }}" class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition text-sm">Browse</a>
                <a href="{{ route('cart.show') }}" class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition text-sm">Cart</a>
                @auth
                    <a href="{{ route('library.index') }}" class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition text-sm">My Library</a>
                    <div class="px-3 py-2 text-sm text-ink-500 dark:text-gray-300">{{ auth()->user()->email }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="px-3">
                        @csrf
                        <button class="w-full px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    (function () {
        const btn = document.querySelector('[data-nav-toggle]');
        const panel = document.querySelector('[data-nav-panel]');
        if (!btn || !panel) return;

        const openIcon = btn.querySelector('[data-nav-icon="open"]');
        const closeIcon = btn.querySelector('[data-nav-icon="close"]');

        function setOpen(isOpen) {
            panel.classList.toggle('hidden', !isOpen);
            openIcon && openIcon.classList.toggle('hidden', isOpen);
            closeIcon && closeIcon.classList.toggle('hidden', !isOpen);
        }

        btn.addEventListener('click', () => setOpen(panel.classList.contains('hidden')));
    })();
</script>

