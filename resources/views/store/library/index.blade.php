<x-layouts.store title="My Library">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">My Library</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Your purchased books and downloads.</p>
        </div>
        <a href="{{ route('books.index') }}" class="text-sm px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Browse more</a>
    </div>

    <div class="mt-6 grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($items as $item)
            @php($b = $item->book)
            <div class="rounded-3xl overflow-hidden border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5">
                <div class="flex">
                    <div class="w-28 aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden shrink-0">
                        @if($b->cover_url)
                            <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full grid place-items-center text-ink-500 dark:text-gray-300 text-xs">No cover</div>
                        @endif
                    </div>
                    <div class="p-4 flex-1">
                        <a href="{{ route('books.show', $b) }}" class="font-semibold leading-tight hover:underline line-clamp-2">{{ $b->title }}</a>
                        <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                        <div class="mt-4 text-xs text-ink-500 dark:text-gray-300">
                            Purchased: {{ optional($item->purchased_at)->format('M j, Y') ?? '—' }}
                        </div>
                        <div class="mt-4 flex items-center justify-between gap-2">
                            <a href="{{ route('library.download', $b) }}" class="px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">
                                Download
                            </a>
                            <div class="text-xs text-ink-500 dark:text-gray-300">
                                Downloads: {{ $item->download_count }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                Your library is empty. Buy a book to see it here.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $items->links() }}
    </div>
</x-layouts.store>

