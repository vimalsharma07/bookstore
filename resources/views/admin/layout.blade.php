<x-layouts.store :title="$title ?? 'Admin'">
    <div class="flex flex-col lg:flex-row gap-6">
        <aside class="lg:w-72">
            <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
                <div class="font-display text-xl">Admin</div>
                <div class="mt-4 flex flex-col gap-2 text-sm">
                    <a class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition" href="{{ route('admin.books.index') }}">Books</a>
                    <a class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition" href="{{ route('admin.categories.index') }}">Categories</a>
                    <a class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition" href="{{ route('admin.orders.index') }}">Orders</a>
                    <a class="px-3 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/10 transition" href="{{ route('admin.reviews.index') }}">Reviews</a>
                </div>
            </div>
        </aside>
        <section class="flex-1">
            {{ $slot }}
        </section>
    </div>
</x-layouts.store>

