<x-layouts.store title="Browse books">
    <div class="flex flex-col lg:flex-row gap-6">
        <aside class="lg:w-80">
            <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
                <div class="font-display text-xl">Search & filters</div>

                <form method="GET" action="{{ route('books.index') }}" class="mt-4 space-y-3">
                    <div>
                        <label class="text-sm text-ink-500 dark:text-gray-300">Search</label>
                        <input name="q" value="{{ $filters['q'] }}"
                               class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none focus:ring-2 focus:ring-amber-200/60"
                               placeholder="Title or author" />
                    </div>

                    <div>
                        <label class="text-sm text-ink-500 dark:text-gray-300">Genre</label>
                        <select name="category" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none">
                            <option value="">All</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}" @selected($filters['category'] === $cat->slug)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-ink-500 dark:text-gray-300">Min price</label>
                            <input name="min" value="{{ $filters['min'] }}"
                                   class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none"
                                   placeholder="0" />
                        </div>
                        <div>
                            <label class="text-sm text-ink-500 dark:text-gray-300">Max price</label>
                            <input name="max" value="{{ $filters['max'] }}"
                                   class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none"
                                   placeholder="50" />
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-ink-500 dark:text-gray-300">Sort</label>
                        <select name="sort" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none">
                            <option value="popular" @selected($filters['sort'] === 'popular')>Popularity</option>
                            <option value="rating" @selected($filters['sort'] === 'rating')>Rating</option>
                            <option value="new" @selected($filters['sort'] === 'new')>Newest</option>
                            <option value="price_low" @selected($filters['sort'] === 'price_low')>Price: low → high</option>
                            <option value="price_high" @selected($filters['sort'] === 'price_high')>Price: high → low</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button class="flex-1 px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">Apply</button>
                        <a href="{{ route('books.index') }}" class="px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Reset</a>
                    </div>
                </form>
            </div>
        </aside>

        <section class="flex-1">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h1 class="font-display text-3xl">Browse</h1>
                    <p class="text-ink-500 dark:text-gray-300 mt-1">{{ $books->total() }} books</p>
                </div>
            </div>

            <div class="mt-6 grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse ($books as $b)
                    <div class="rounded-3xl overflow-hidden border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 card-hover">
                            <div class="flex">
                                <div class="w-28 sm:w-32 aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden shrink-0">
                                    <a href="{{ route('books.show', $b) }}" class="block h-full w-full">
                                        <x-book-cover :book="$b" size="sm" class="rounded-none" />
                                    </a>
                                </div>
                                <div class="p-4 flex-1">
                                    <a href="{{ route('books.show', $b) }}" class="font-semibold text-lg leading-tight line-clamp-2 hover:underline">{{ $b->title }}</a>
                                    <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach ($b->categories->take(2) as $cat)
                                            <span class="text-xs px-2 py-1 rounded-full bg-parchment-100 dark:bg-white/10 border border-black/5 dark:border-white/10">{{ $cat->name }}</span>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="text-sm font-medium">{{ $b->display_price }}</div>
                                        <div class="text-xs text-ink-500 dark:text-gray-300">★ {{ number_format((float)$b->rating_avg, 1) }}</div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-2">
                                        <form method="POST" action="{{ route('cart.add', $b) }}">
                                            @csrf
                                            <button class="px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">
                                                Add to cart
                                            </button>
                                        </form>
                                        <a href="{{ route('books.show', $b) }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                        No books found. Try a different search.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $books->links() }}
            </div>
        </section>
    </div>
</x-layouts.store>

