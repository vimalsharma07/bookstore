<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center h-13 px-6 rounded-2xl bg-ink-900 border border-transparent font-semibold text-sm text-white uppercase tracking-wide hover:bg-black focus:outline-none focus:ring-2 focus:ring-amber-300/60 focus:ring-offset-2 focus:ring-offset-transparent transition']) }}>
    {{ $slot }}
</button>
