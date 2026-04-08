<x-layouts.store :title="$book->title">
    <div class="grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-4">
            <div class="rounded-3xl overflow-hidden border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 shadow-soft">
                <div class="aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden">
                    <x-book-cover :book="$book" class="rounded-none" />
                </div>
                <div class="p-5">
                    <div class="text-sm text-ink-500 dark:text-gray-300">Price</div>
                    <div class="mt-1 font-display text-2xl">{{ $book->display_price }}</div>
                    <div class="mt-3 flex items-center justify-between text-sm text-ink-500 dark:text-gray-300">
                        <span>★ {{ number_format((float)$book->rating_avg, 1) }}</span>
                        <span>{{ $book->reviews_count }} reviews</span>
                    </div>

                    <div class="mt-5 space-y-2">
                        @auth
                            @if($owned)
                                <a href="{{ route('library.download', $book) }}" class="w-full inline-flex justify-center px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                                    Download
                                </a>
                                <a href="{{ route('library.index') }}" class="w-full inline-flex justify-center px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                                    View in My Library
                                </a>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <form method="POST" action="{{ route('cart.add', $book) }}">
                                        @csrf
                                        <button class="w-full inline-flex justify-center px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                                            Add to cart
                                        </button>
                                    </form>

                                    <a href="{{ route('cart.show') }}" class="w-full inline-flex justify-center px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                                        View cart
                                    </a>
                                </div>

                                <form method="POST" action="{{ route('checkout.start', $book) }}">
                                    @csrf
                                    <button class="w-full inline-flex justify-center px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                                        Buy now (single)
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('books.wishlist', $book) }}">
                                @csrf
                                <button class="w-full inline-flex justify-center px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                                    {{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}
                                </button>
                            </form>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="w-full inline-flex justify-center px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
                                Log in to buy
                            </a>
                        @endguest

                        @if($book->preview_pdf_path)
                            <a target="_blank" href="{{ route('books.preview', $book) }}" class="w-full inline-flex justify-center px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">
                                Preview sample
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="flex flex-wrap gap-2">
                @foreach ($book->categories as $cat)
                    <a href="{{ route('books.index', ['category' => $cat->slug]) }}" class="text-xs px-3 py-1.5 rounded-full bg-white/70 dark:bg-white/5 border border-black/5 dark:border-white/10 hover:bg-white/90 dark:hover:bg-white/10 transition">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <h1 class="mt-4 font-display text-4xl tracking-tight">{{ $book->title }}</h1>
            <p class="mt-2 text-lg text-ink-500 dark:text-gray-300">By {{ $book->author }}</p>

            <div class="mt-6 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-2xl">About this book</h2>
                <div class="prose prose-zinc max-w-none mt-3 dark:prose-invert">
                    {!! nl2br(e($book->description)) !!}
                </div>
            </div>

            <div class="mt-8 grid lg:grid-cols-2 gap-6">
                <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                    <h2 class="font-display text-2xl">Reviews</h2>
                    <div class="mt-4 space-y-4">
                        @forelse ($reviews as $r)
                            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-medium">{{ $r->user->name }}</div>
                                    <div class="text-xs text-ink-500 dark:text-gray-300">★ {{ $r->rating }}</div>
                                </div>
                                @if($r->title)
                                    <div class="mt-2 font-semibold">{{ $r->title }}</div>
                                @endif
                                @if($r->body)
                                    <div class="mt-2 text-sm text-ink-500 dark:text-gray-300">{!! nl2br(e($r->body)) !!}</div>
                                @endif
                            </div>
                        @empty
                            <div class="text-sm text-ink-500 dark:text-gray-300">No reviews yet.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                    <h2 class="font-display text-2xl">Write a review</h2>
                    @auth
                        <form method="POST" action="{{ route('books.reviews.store', $book) }}" class="mt-4 space-y-3">
                            @csrf
                            <div>
                                <label class="text-sm text-ink-500 dark:text-gray-300">Rating (1-5)</label>
                                <input name="rating" type="number" min="1" max="5" required
                                       class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none" />
                            </div>
                            <div>
                                <label class="text-sm text-ink-500 dark:text-gray-300">Title</label>
                                <input name="title"
                                       class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none" />
                            </div>
                            <div>
                                <label class="text-sm text-ink-500 dark:text-gray-300">Review</label>
                                <textarea name="body" rows="4"
                                          class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 focus:outline-none"></textarea>
                            </div>
                            <button class="px-4 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">Submit</button>
                        </form>
                    @else
                        <div class="mt-3 text-sm text-ink-500 dark:text-gray-300">
                            <a class="underline" href="{{ route('login') }}">Log in</a> to leave a review.
                        </div>
                    @endauth
                </div>
            </div>

            @if ($recommended->isNotEmpty())
                <div class="mt-10">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <h2 class="font-display text-2xl">Recommended</h2>
                            <p class="text-ink-500 dark:text-gray-300 mt-1">More books like this.</p>
                        </div>
                        <a href="{{ route('books.index') }}" class="text-sm px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Browse</a>
                    </div>

                    <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($recommended as $b)
                            <a href="{{ route('books.show', $b) }}" class="group">
                                <div class="rounded-3xl overflow-hidden border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 card-hover">
                                    <div class="flex">
                                        <div class="w-28 aspect-[3/4] bg-parchment-100 dark:bg-white/5 overflow-hidden shrink-0">
                                            @if($b->cover_url)
                                                <img src="{{ $b->cover_url }}" alt="{{ $b->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition" />
                                            @else
                                                <div class="h-full w-full grid place-items-center text-ink-500 dark:text-gray-300 text-xs">No cover</div>
                                            @endif
                                        </div>
                                        <div class="p-4 flex-1">
                                            <div class="font-semibold leading-tight line-clamp-2">{{ $b->title }}</div>
                                            <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">{{ $b->author }}</div>
                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="text-sm font-medium">{{ $b->display_price }}</div>
                                                <div class="text-xs text-ink-500 dark:text-gray-300">★ {{ number_format((float)$b->rating_avg, 1) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.store>

