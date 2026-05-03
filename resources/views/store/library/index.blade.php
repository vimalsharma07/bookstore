<x-layouts.store title="My Library">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">My Library</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Purchased books and subscription reading.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('subscriptions.index') }}" class="text-sm px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Subscriptions</a>
            <a href="{{ route('books.index') }}" class="text-sm px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Browse more</a>
        </div>
    </div>

    @if($readingUnlimited)
        <div class="mt-6 rounded-2xl border border-emerald-200/80 bg-emerald-50/90 dark:bg-emerald-950/35 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-950 dark:text-emerald-100">
            <strong>Active subscription</strong> — full catalog access until {{ $readingUnlimited->ends_at->format('M j, Y') }}. Open any book and download while your pass is active.
        </div>
    @endif

    @if($readingCustom && $readingCustom->books->isNotEmpty())
        <div class="mt-8">
            <h2 class="font-display text-xl">Reading with subscription</h2>
            <p class="text-sm text-ink-500 dark:text-gray-400 mt-1">
                {{ $readingCustom->books->count() }}/{{ $readingCustom->max_books }} books · ends {{ $readingCustom->ends_at->format('M j, Y') }}
            </p>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($readingCustom->books as $b)
                    <div class="rounded-2xl overflow-hidden border border-black/6 dark:border-white/10 bg-white/75 dark:bg-white/[0.06] flex flex-col">
                        <a href="{{ route('books.show', $b) }}" class="block aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden">
                            @if($b->cover_url)
                                <img src="{{ $b->cover_url }}" alt="" class="h-full w-full object-cover hover:scale-[1.02] transition-transform duration-300" />
                            @else
                                <div class="h-full w-full grid place-items-center text-ink-500 text-xs">No cover</div>
                            @endif
                        </a>
                        <div class="p-4 flex-1 flex flex-col">
                            <a href="{{ route('books.show', $b) }}" class="font-semibold leading-snug line-clamp-2 hover:underline">{{ $b->title }}</a>
                            <div class="text-sm text-ink-500 dark:text-gray-400 mt-1">{{ $b->author }}</div>
                            <a href="{{ route('library.download', $b) }}" class="mt-4 text-center py-2.5 rounded-xl bg-ink-900 text-white text-sm hover:bg-black transition">
                                Download
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-10">
        <h2 class="font-display text-xl">Purchased</h2>
    </div>

    <div class="mt-4 grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($items as $item)
            @php($b = $item->book)
            <div class="rounded-2xl overflow-hidden border border-black/6 dark:border-white/10 bg-white/75 dark:bg-white/[0.06] flex flex-col">
                <a href="{{ route('books.show', $b) }}" class="block aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden">
                    @if($b->cover_url)
                        <img src="{{ $b->cover_url }}" alt="" class="h-full w-full object-cover" />
                    @else
                        <div class="h-full w-full grid place-items-center text-ink-500 dark:text-gray-300 text-xs">No cover</div>
                    @endif
                </a>
                <div class="p-4 flex-1 flex flex-col">
                    <a href="{{ route('books.show', $b) }}" class="font-semibold leading-snug line-clamp-2 hover:underline">{{ $b->title }}</a>
                    <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                    <div class="mt-3 text-xs text-ink-500 dark:text-gray-400">
                        Purchased {{ optional($item->purchased_at)->format('M j, Y') ?? '—' }}
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-2">
                        <a href="{{ route('library.download', $b) }}" class="inline-flex flex-1 justify-center px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">
                            Download
                        </a>
                        <span class="text-xs text-ink-500 dark:text-gray-400 whitespace-nowrap">{{ $item->download_count }}×</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-8 text-center text-ink-600 dark:text-gray-300">
                @if($readingUnlimited || ($readingCustom && $readingCustom->books->isNotEmpty()))
                    You have no individual purchases yet. Subscription access is summarized above.
                @else
                    No purchases yet. <a href="{{ route('books.index') }}" class="underline font-medium">Browse books</a> or <a href="{{ route('subscriptions.index') }}" class="underline font-medium">view subscriptions</a>.
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $items->links() }}
    </div>
</x-layouts.store>
