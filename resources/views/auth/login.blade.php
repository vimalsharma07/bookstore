<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="max-w-lg mx-auto">
        <div class="mb-6">
            <div class="font-display text-5xl tracking-tight">Welcome back</div>
            <div class="mt-2 text-lg text-ink-500 dark:text-gray-300">Log in to access your library and downloads.</div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-base" />
                <x-text-input id="email" class="block mt-2"
                              type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-base" />
                <x-text-input id="password" class="block mt-2"
                              type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-black/10 dark:border-white/15 text-black shadow-sm focus:ring-0" name="remember">
                    <span class="ms-2 text-sm text-ink-500 dark:text-gray-300">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-ink-500 dark:text-gray-300 hover:text-ink-900 dark:hover:text-white underline underline-offset-4"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </form>

        <div class="mt-6 text-base text-ink-500 dark:text-gray-300 text-center">
            Don’t have an account?
            <a class="font-medium text-ink-900 dark:text-white underline underline-offset-4" href="{{ route('register') }}">Sign up</a>
        </div>
    </div>
</x-guest-layout>
