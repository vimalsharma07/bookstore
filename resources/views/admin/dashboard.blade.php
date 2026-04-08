<x-admin.layout title="Admin · Dashboard">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-4xl tracking-tight">Dashboard</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Quick overview of your bookstore operations.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="px-5 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">
            + Add Book
        </a>
    </div>

    <div class="mt-6 grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
            <div class="text-sm text-ink-500 dark:text-gray-300">Total Books</div>
            <div class="mt-2 font-display text-3xl">{{ $stats['books'] }}</div>
        </div>
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
            <div class="text-sm text-ink-500 dark:text-gray-300">Published Books</div>
            <div class="mt-2 font-display text-3xl">{{ $stats['published_books'] }}</div>
        </div>
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
            <div class="text-sm text-ink-500 dark:text-gray-300">Categories</div>
            <div class="mt-2 font-display text-3xl">{{ $stats['categories'] }}</div>
        </div>
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
            <div class="text-sm text-ink-500 dark:text-gray-300">Orders</div>
            <div class="mt-2 font-display text-3xl">{{ $stats['orders'] }}</div>
        </div>
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
            <div class="text-sm text-ink-500 dark:text-gray-300">Paid Orders</div>
            <div class="mt-2 font-display text-3xl">{{ $stats['paid_orders'] }}</div>
        </div>
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-5">
            <div class="text-sm text-ink-500 dark:text-gray-300">Revenue</div>
            <div class="mt-2 font-display text-3xl">USD {{ number_format($stats['revenue_cents'] / 100, 2) }}</div>
        </div>
    </div>

    <div class="mt-8 grid xl:grid-cols-2 gap-6">
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-2xl">Latest Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">View all</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse($latestOrders as $o)
                    <a href="{{ route('admin.orders.show', $o) }}" class="block rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 hover:bg-white/90 dark:hover:bg-white/10 transition">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="font-medium">Order #{{ $o->id }}</div>
                                <div class="text-xs text-ink-500 dark:text-gray-300">{{ $o->user?->email ?? $o->email ?? '—' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm">{{ strtoupper($o->currency) }} {{ number_format($o->total_cents / 100, 2) }}</div>
                                <div class="text-xs text-ink-500 dark:text-gray-300">{{ $o->status }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-sm text-ink-500 dark:text-gray-300">No orders yet.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-2xl">Latest Books</h2>
                <a href="{{ route('admin.books.index') }}" class="text-sm px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Manage books</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse($latestBooks as $b)
                    <a href="{{ route('admin.books.edit', $b) }}" class="block rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4 hover:bg-white/90 dark:hover:bg-white/10 transition">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="font-medium">{{ $b->title }}</div>
                                <div class="text-xs text-ink-500 dark:text-gray-300">{{ $b->author }}</div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $b->is_active ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                                {{ $b->is_active ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-sm text-ink-500 dark:text-gray-300">No books yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin.layout>

