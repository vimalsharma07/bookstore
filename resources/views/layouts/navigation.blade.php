<nav class="bg-white/70 dark:bg-white/5 backdrop-blur border-b border-black/5 dark:border-white/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-ink-900 dark:text-[#F3F2EE]" />
                        <span class="hidden sm:block font-display text-lg tracking-tight">BookQueue
</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <button type="button"
                        onclick="window.__toggleTheme?.()"
                        class="inline-flex items-center justify-center h-10 w-10 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                    <span class="sr-only">Toggle theme</span>
                    <svg class="h-5 w-5 text-ink-700 dark:text-[#F3F2EE]" viewBox="0 0 24 24" fill="none">
                        <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 2v2m0 16v2M4 12H2m20 0h-2M5 5l1.5 1.5M17.5 17.5 19 19M19 5l-1.5 1.5M6.5 17.5 5 19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button type="button" data-nav-toggle class="inline-flex items-center justify-center p-2 rounded-xl text-gray-600 dark:text-gray-200 hover:bg-black/5 dark:hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path data-nav-icon="open" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path data-nav-icon="close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div data-nav-panel class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-2 border-t border-black/10 dark:border-white/10">
            <div class="px-4">
                <div class="font-medium text-base text-ink-900 dark:text-[#F3F2EE]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-ink-500 dark:text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <div class="px-4">
                    <button type="button"
                            onclick="window.__toggleTheme?.()"
                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">
                        <span>Toggle theme</span>
                    </button>
                </div>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
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
