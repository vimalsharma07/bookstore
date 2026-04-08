@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full h-13 px-4 text-[17px] leading-6 rounded-2xl border border-black/10 dark:border-white/15 bg-white/85 dark:bg-white/5 text-ink-900 dark:text-white placeholder:text-ink-500/80 dark:placeholder:text-gray-300/70 focus:outline-none focus:ring-2 focus:ring-amber-200/70 dark:focus:ring-amber-300/30 focus:border-transparent transition']) }}>
