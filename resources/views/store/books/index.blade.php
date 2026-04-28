<x-layouts.store title="Browse books">
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">
        {{-- Narrower sidebar so the grid gets more width --}}
        <aside class="w-full lg:max-w-[17rem] lg:shrink-0">
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/80 dark:bg-white/[0.06] p-4 sm:p-5 shadow-sm">
                <div class="font-display text-lg tracking-tight">Filters</div>
                <p class="text-xs text-ink-500 dark:text-gray-400 mt-0.5">Refine your search</p>

                <form method="GET" action="{{ route('books.index') }}" class="mt-4 space-y-3.5">
                    <div>
                        <label class="text-xs font-medium text-ink-600 dark:text-gray-300">Search</label>
                        <input name="q" value="{{ $filters['q'] }}"
                               class="mt-1.5 w-full px-3 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white dark:bg-white/5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-200/60 dark:focus:ring-amber-500/30"
                               placeholder="Title or author" />
                    </div>

                    <div>
                        <label class="text-xs font-medium text-ink-600 dark:text-gray-300">Genre</label>
                        <select name="category" class="mt-1.5 w-full px-3 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white dark:bg-white/5 text-sm focus:outline-none">
                            <option value="">All genres</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}" @selected($filters['category'] === $cat->slug)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="text-xs font-medium text-ink-600 dark:text-gray-300">Min</label>
                            <input name="min" value="{{ $filters['min'] }}"
                                   class="mt-1.5 w-full px-3 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white dark:bg-white/5 text-sm focus:outline-none"
                                   placeholder="0" />
                        </div>
                        <div>
                            <label class="text-xs font-medium text-ink-600 dark:text-gray-300">Max</label>
                            <input name="max" value="{{ $filters['max'] }}"
                                   class="mt-1.5 w-full px-3 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white dark:bg-white/5 text-sm focus:outline-none"
                                   placeholder="50" />
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-ink-600 dark:text-gray-300">Sort</label>
                        <select name="sort" class="mt-1.5 w-full px-3 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white dark:bg-white/5 text-sm focus:outline-none">
                            <option value="popular" @selected($filters['sort'] === 'popular')>Popularity</option>
                            <option value="rating" @selected($filters['sort'] === 'rating')>Rating</option>
                            <option value="new" @selected($filters['sort'] === 'new')>Newest</option>
                            <option value="price_low" @selected($filters['sort'] === 'price_low')>Price: low → high</option>
                            <option value="price_high" @selected($filters['sort'] === 'price_high')>Price: high → low</option>
                        </select>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 px-3 py-2.5 rounded-xl bg-ink-900 text-white text-sm font-medium hover:bg-black transition">Apply</button>
                        <a href="{{ route('books.index') }}" class="flex-1 px-3 py-2.5 rounded-xl border border-black/10 dark:border-white/15 text-sm font-medium text-center hover:bg-black/5 dark:hover:bg-white/10 transition">Reset</a>
                    </div>
                </form>
            </div>
        </aside>

        <section class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <div>
                    <h1 class="font-display text-3xl sm:text-4xl tracking-tight">Browse</h1>
                    <p class="text-sm text-ink-500 dark:text-gray-400 mt-1">{{ $books->total() }} {{ $books->total() === 1 ? 'book' : 'books' }}</p>
                </div>
            </div>

            {{-- Vertical cards: cover on top, room for title + actions --}}
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-7">
                @forelse ($books as $b)
                    <article class="group flex flex-col h-full rounded-2xl overflow-hidden border border-black/6 dark:border-white/10 bg-white/75 dark:bg-white/[0.06] shadow-sm hover:shadow-md hover:border-black/10 dark:hover:border-white/15 transition-all duration-200">
                        <a href="{{ route('books.show', $b) }}" class="relative block aspect-[3/4] overflow-hidden bg-parchment-100 dark:bg-white/5">
                            <x-book-cover :book="$b" size="md" class="h-full w-full rounded-none transition-transform duration-300 group-hover:scale-[1.03]" />
                            <span class="sr-only">{{ $b->title }}</span>
                        </a>

                        <div class="flex flex-col flex-1 p-4 sm:p-4 min-h-0">
                            <a href="{{ route('books.show', $b) }}" class="font-semibold text-[15px] sm:text-base leading-snug line-clamp-2 text-ink-900 dark:text-white hover:text-ink-700 dark:hover:text-gray-100 transition">{{ $b->title }}</a>
                            <p class="text-sm text-ink-500 dark:text-gray-400 mt-1 line-clamp-1">{{ $b->author }}</p>

                            @if($b->categories->isNotEmpty())
                                <div class="mt-2.5 flex flex-wrap gap-1.5">
                                    @foreach ($b->categories->take(2) as $cat)
                                        <span class="text-[11px] leading-none px-2 py-1 rounded-md bg-black/[0.04] dark:bg-white/10 text-ink-600 dark:text-gray-300">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-3 flex items-baseline justify-between gap-2">
                                <span class="text-base font-semibold text-ink-900 dark:text-white tabular-nums">{{ $b->display_price }}</span>
                                <span class="text-xs text-ink-500 dark:text-gray-400 shrink-0 tabular-nums">★ {{ number_format((float)$b->rating_avg, 1) }}</span>
                            </div>

                            <div class="mt-auto pt-4 flex flex-col gap-2">
                                <form method="POST" action="{{ route('cart.add', $b) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-ink-900 text-white text-sm font-medium hover:bg-black transition whitespace-nowrap">
                                        Add to cart
                                    </button>
                                </form>
                                <a href="{{ route('books.show', $b) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-black/12 dark:border-white/15 text-sm font-medium text-ink-800 dark:text-gray-200 hover:bg-black/[0.04] dark:hover:bg-white/10 transition">
                                    View details
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-8 text-center text-ink-600 dark:text-gray-300">
                        No books match your filters. Try adjusting search or reset.
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $books->links() }}
            </div>
        </section>
    </div>
</x-layouts.store>
