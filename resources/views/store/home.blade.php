<x-layouts.store :title="config('app.name', 'BookQueue')">
    <style>
        @keyframes hero-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .hero-cover-pulse { animation: hero-float 5s ease-in-out infinite; }
        .hero-strip > a:nth-child(2) .hero-cover-pulse { animation-delay: 0.5s; }
        .hero-strip > a:nth-child(3) .hero-cover-pulse { animation-delay: 1s; }
        .hero-strip > a:nth-child(4) .hero-cover-pulse { animation-delay: 1.5s; }
        .hero-marquee { animation: hero-scroll 28s linear infinite; }
        @keyframes hero-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>

    {{-- items-start: avoid vertical centering that left empty space above the headline vs. tall right column --}}
    <section class="relative grid lg:grid-cols-12 gap-8 lg:gap-10 items-start">
        <div class="lg:col-span-7 relative z-10">
            {{-- Ambient shapes + live cover strip fills the "empty" band under the nav --}}
            <div class="relative mb-8 -mx-1 sm:mx-0">
                <div class="pointer-events-none absolute -top-6 -left-4 w-40 h-40 rounded-full bg-amber-200/35 dark:bg-amber-500/10 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute top-0 right-0 w-32 h-32 rounded-full bg-rose-200/30 dark:bg-rose-500/10 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute bottom-0 left-1/3 w-24 h-24 rounded-full bg-sky-200/25 dark:bg-sky-500/10 blur-2xl" aria-hidden="true"></div>

                <div class="hero-strip relative flex items-end justify-center gap-2 sm:gap-3 min-h-[7.5rem] sm:min-h-[9rem]">
                    @forelse ($heroStrip as $b)
                        <a href="{{ route('books.show', $b) }}"
                           class="group relative w-[4.5rem] sm:w-24 shrink-0 rounded-xl overflow-hidden shadow-lg ring-2 ring-white/80 dark:ring-white/10 {{ $loop->iteration === 1 ? '-rotate-6 translate-y-2' : ($loop->iteration === 2 ? '-rotate-2 translate-y-0' : ($loop->iteration === 3 ? 'rotate-2 translate-y-0' : 'rotate-6 translate-y-2')) }} hover:ring-amber-200/60 hover:z-20 transition">
                            <div class="hero-cover-pulse aspect-[3/4] bg-parchment-200 dark:bg-white/10">
                                @if($b->cover_url)
                                    <img src="{{ $b->cover_url }}" alt="" class="h-full w-full object-cover group-hover:scale-105 transition" />
                                @else
                                    <div class="h-full w-full grid place-items-center text-[10px] text-center px-1 text-ink-600 dark:text-gray-400 leading-tight">{{ \Illuminate\Support\Str::limit($b->title, 24) }}</div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="flex gap-3 opacity-50">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="w-16 sm:w-20 aspect-[3/4] rounded-xl bg-gradient-to-br from-parchment-200 to-parchment-100 dark:from-white/10 dark:to-white/5 border border-black/5"></div>
                            @endfor
                        </div>
                    @endforelse
                </div>

                @if ($heroStrip->isNotEmpty())
                    <div class="mt-4 relative overflow-hidden rounded-2xl border border-black/5 dark:border-white/10 bg-white/50 dark:bg-white/5 py-2">
                        <div class="hero-marquee flex gap-6 whitespace-nowrap px-4">
                            @foreach ($heroMarquee as $b)
                                <a href="{{ route('books.show', $b) }}" class="inline-flex items-center gap-2 text-sm text-ink-700 dark:text-gray-200 hover:text-ink-900 dark:hover:text-white">
                                    <span class="font-medium truncate max-w-[10rem]">{{ $b->title }}</span>
                                    <span class="text-ink-500 dark:text-gray-400">·</span>
                                    <span class="text-amber-700/80 dark:text-amber-300/80">★ {{ number_format((float) $b->rating_avg, 1) }}</span>
                                </a>
                            @endforeach
                            @foreach ($heroMarquee as $b)
                                <a href="{{ route('books.show', $b) }}" class="inline-flex items-center gap-2 text-sm text-ink-700 dark:text-gray-200 hover:text-ink-900 dark:hover:text-white">
                                    <span class="font-medium truncate max-w-[10rem]">{{ $b->title }}</span>
                                    <span class="text-ink-500 dark:text-gray-400">·</span>
                                    <span class="text-amber-700/80 dark:text-amber-300/80">★ {{ number_format((float) $b->rating_avg, 1) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/70 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm">
                    <span class="h-2 w-2 rounded-full bg-rose-300"></span>
                    <span class="text-ink-700 dark:text-gray-200">A soft place to read and discover</span>
                </div>
                @if ($stats['books'] > 0)
                    <span class="text-xs px-2.5 py-1 rounded-full bg-white/60 dark:bg-white/5 border border-black/5 dark:border-white/10 text-ink-600 dark:text-gray-300">
                        {{ number_format($stats['books']) }} titles
                    </span>
                @endif
                @if ($stats['reviews'] > 0)
                    <span class="text-xs px-2.5 py-1 rounded-full bg-white/60 dark:bg-white/5 border border-black/5 dark:border-white/10 text-ink-600 dark:text-gray-300">
                        {{ number_format($stats['reviews']) }} reviews
                    </span>
                @endif
            </div>

            <h1 class="mt-5 font-display text-4xl sm:text-5xl leading-tight tracking-tight">
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

        <div class="lg:col-span-5 lg:sticky lg:top-24">
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

