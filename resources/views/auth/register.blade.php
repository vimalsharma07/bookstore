<x-guest-layout>
    <div class="max-w-lg mx-auto">
        <div class="mb-6">
            <div class="font-display text-5xl tracking-tight">Create your account</div>
            <div class="mt-2 text-lg text-ink-500 dark:text-gray-300">Save favorites, buy books, and build your library.</div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" class="text-base" />
                <x-text-input id="name" class="block mt-2"
                              type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-base" />
                <x-text-input id="email" class="block mt-2"
                              type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-base" />
                <x-text-input id="password" class="block mt-2"
                              type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-base" />
                <x-text-input id="password_confirmation" class="block mt-2"
                              type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button class="w-full">
                {{ __('Sign up') }}
            </x-primary-button>
        </form>

        <div class="mt-6 text-base text-ink-500 dark:text-gray-300 text-center">
            Already have an account?
            <a class="font-medium text-ink-900 dark:text-white underline underline-offset-4" href="{{ route('login') }}">Log in</a>
        </div>
    </div>
</x-guest-layout>
