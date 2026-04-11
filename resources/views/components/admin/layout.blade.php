<x-layouts.store :title="$title ?? 'Admin'">
    <div class="flex flex-col lg:flex-row gap-6">
        <aside class="lg:w-80">
            <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5 sticky top-24">
                <div class="font-display text-2xl">Admin Panel</div>
                <div class="text-sm text-ink-500 dark:text-gray-300 mt-1">Manage books, categories, orders, and reviews.</div>

                <div class="mt-5 flex flex-col gap-2 text-sm">
                    <a class="px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.home') ? 'bg-ink-900 text-white' : 'hover:bg-black/5 dark:hover:bg-white/10' }}"
                       href="{{ route('admin.home') }}">Dashboard</a>

                    <a class="px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.books.*') ? 'bg-ink-900 text-white' : 'hover:bg-black/5 dark:hover:bg-white/10' }}"
                       href="{{ route('admin.books.index') }}">Books</a>

                    <a class="px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-ink-900 text-white' : 'hover:bg-black/5 dark:hover:bg-white/10' }}"
                       href="{{ route('admin.categories.index') }}">Categories</a>

                    <a class="px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-ink-900 text-white' : 'hover:bg-black/5 dark:hover:bg-white/10' }}"
                       href="{{ route('admin.orders.index') }}">Orders</a>

                    <a class="px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.reviews.*') ? 'bg-ink-900 text-white' : 'hover:bg-black/5 dark:hover:bg-white/10' }}"
                       href="{{ route('admin.reviews.index') }}">Reviews</a>
                </div>

                <div class="mt-6 pt-5 border-t border-black/10 dark:border-white/10">
                    <div class="text-xs font-semibold uppercase tracking-wide text-ink-500 dark:text-gray-400">Reviews</div>
                    <div class="mt-2 flex flex-col gap-2">
                        <a href="{{ route('admin.reviews.index') }}"
                           class="w-full inline-flex justify-center px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm font-medium">
                            All reviews
                        </a>
                        <a href="{{ route('admin.reviews.create') }}"
                           class="w-full inline-flex justify-center px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">
                            + Add review
                        </a>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-black/10 dark:border-white/10 space-y-2">
                    <a href="{{ route('admin.books.create') }}" class="w-full inline-flex justify-center px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">
                        + Add New Book
                    </a>
                    <a href="{{ route('books.index') }}" class="w-full inline-flex justify-center px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">
                        View Storefront
                    </a>
                </div>
            </div>
        </aside>
        <section class="flex-1">
            {{ $slot }}
        </section>
    </div>
</x-layouts.store>

