<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=fraunces:400,600,700&display=swap" rel="stylesheet" />

        <!-- Styles (no Vite / no Node required) -->
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
        </style>
        @stack('head')
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
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-parchment-50 to-parchment-100/40 dark:from-[#0B0B0A] dark:to-[#10100F] text-ink-900 dark:text-[#F3F2EE]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-8">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-ink-700/80 dark:text-gray-300" />
                </a>
            </div>

            <div class="w-full sm:max-w-xl mt-6 px-8 py-8 bg-white/85 dark:bg-white/5 backdrop-blur border border-black/5 dark:border-white/10 shadow-soft overflow-hidden rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
