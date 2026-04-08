<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'BookQueue
') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=fraunces:400,600,700&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                            display: ['Fraunces', 'ui-serif', 'Georgia', 'serif'],
                        },
                        colors: {
                            parchment: {
                                50: '#FFFBF5',
                                100: '#FDF4E7',
                                200: '#F8E8D2',
                                300: '#EFD4AE',
                            },
                            ink: {
                                900: '#1A1A17',
                                700: '#3A3A34',
                                500: '#5A5A53',
                            },
                        },
                        boxShadow: {
                            soft: '0 10px 30px rgba(17, 24, 39, 0.08)',
                        },
                    },
                },
            }
        </script>
        <style>
            :root { color-scheme: light; }
            html.dark { color-scheme: dark; }
            .card-hover { transition: transform .18s ease, box-shadow .18s ease; }
            .card-hover:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(17,24,39,.10); }
        </style>
        <script>
            (function () {
                const key = 'theme';
                const saved = localStorage.getItem(key);
                // Default to light; only use dark when user explicitly chose it
                const shouldDark = saved === 'dark';
                document.documentElement.classList.toggle('dark', shouldDark);
            })();
            window.__toggleTheme = function () {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            };
        </script>
        @stack('head')
    </head>
    <body class="font-sans antialiased bg-parchment-50 text-ink-900 dark:bg-[#0B0B0A] dark:text-[#F3F2EE]">
        @include('partials.site-nav')

        @if (session('status'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 backdrop-blur px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        <footer class="border-t border-black/5 dark:border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 text-sm text-ink-500 dark:text-gray-300 flex flex-col lg:flex-row gap-6 lg:items-center lg:justify-between">
                <div>
                    <div class="font-display text-base text-ink-900 dark:text-white">BookQueue
</div>
                    <div class="mt-1">Built for comfortable reading and discovery.</div>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('pages.faq') }}" class="hover:text-ink-900 dark:hover:text-white underline-offset-4 hover:underline transition">FAQ</a>
                    <a href="{{ route('pages.privacy') }}" class="hover:text-ink-900 dark:hover:text-white underline-offset-4 hover:underline transition">Privacy Policy</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-ink-900 dark:hover:text-white underline-offset-4 hover:underline transition">Terms & Conditions</a>
                </div>
            </div>
        </footer>
    </body>
</html>

