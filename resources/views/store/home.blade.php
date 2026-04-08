<x-layouts.store :title="config('app.name', 'BookQueue
')">
    <section class="grid lg:grid-cols-12 gap-6 items-center">
        <div class="lg:col-span-7">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/70 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm">
                <span class="h-2 w-2 rounded-full bg-rose-300"></span>
                <span class="text-ink-700 dark:text-gray-200">A soft place to read and discover</span>
            </div>
            <h1 class="mt-4 font-display text-4xl sm:text-5xl leading-tight tracking-tight">
                Find your next favorite book — and keep it forever.
            </h1>
            <p class="mt-4 text-lg text-ink-500 dark:text-gray-300 max-w-xl">
                Curated eBooks with clean typography, warm visuals, and a library that feels like home.
            </p>

            <form action="{{ route('books.index') }}" method="GET" class="mt-6 flex flex-col sm:flex-row gap-3">
                <input name="q" placeholder="Search by title or author..."
                       class="flex-1 px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 backdrop-blur focus:outline-none focus:ring-2 focus:ring-amber-200/60" />
                <button class="px-5 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                    Browse books
                </button>
            </form>

            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($categories->take(8) as $cat)
                    <a href="{{ route('books.index', ['category' => $cat->slug]) }}"
                       class="px-3 py-2 rounded-xl bg-white/60 dark:bg-white/5 border border-black/5 dark:border-white/10 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">
                        {{ $cat->name }}
                        <span class="text-ink-500 dark:text-gray-300">({{ $cat->books_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-gradient-to-br from-white/80 to-parchment-100 dark:from-white/10 dark:to-white/5 shadow-soft overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="text-sm text-ink-500 dark:text-gray-300">Featured</div>
                    <div class="mt-2 font-display text-2xl">Top picks for cozy reading</div>
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        @foreach ($featured->take(4) as $b)
                            <a href="{{ route('books.show', $b) }}" class="group">
                                <div class="rounded-2xl overflow-hidden border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 card-hover">
                                    <div class="aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden">
                                        @if($b->cover_url)
                                            <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition" />
                                        @else
                                            <div class="h-full w-full grid place-items-center text-ink-500 dark:text-gray-300 text-sm">No cover</div>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <div class="font-medium leading-tight line-clamp-2">{{ $b->title }}</div>
                                        <div class="text-xs text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-12">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl">Featured books</h2>
                <p class="text-ink-500 dark:text-gray-300 mt-1">Highly rated and loved by readers.</p>
            </div>
            <a href="{{ route('books.index') }}" class="text-sm px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">View all</a>
        </div>

        <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($featured as $b)
                <a href="{{ route('books.show', $b) }}" class="group">
                    <div class="rounded-3xl overflow-hidden border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 card-hover">
                        <div class="aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden">
                            @if($b->cover_url)
                                <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition" />
                            @else
                                <div class="h-full w-full grid place-items-center text-ink-500 dark:text-gray-300 text-sm">No cover</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="font-semibold leading-tight line-clamp-2">{{ $b->title }}</div>
                            <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-sm font-medium">{{ $b->display_price }}</div>
                                <div class="text-xs text-ink-500 dark:text-gray-300">★ {{ number_format((float)$b->rating_avg, 1) }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    @auth
        @if ($recommended->isNotEmpty())
            <section class="mt-12">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="font-display text-2xl">Recommended for you</h2>
                        <p class="text-ink-500 dark:text-gray-300 mt-1">Based on your library and favorites.</p>
                    </div>
                </div>

                <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($recommended as $b)
                        <a href="{{ route('books.show', $b) }}" class="group">
                            <div class="rounded-3xl overflow-hidden border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 card-hover">
                                <div class="aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden">
                                    @if($b->cover_url)
                                        <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition" />
                                    @else
                                        <div class="h-full w-full grid place-items-center text-ink-500 dark:text-gray-300 text-sm">No cover</div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="font-semibold leading-tight line-clamp-2">{{ $b->title }}</div>
                                    <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="text-sm font-medium">{{ $b->display_price }}</div>
                                        <div class="text-xs text-ink-500 dark:text-gray-300">★ {{ number_format((float)$b->rating_avg, 1) }}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endauth
</x-layouts.store>

