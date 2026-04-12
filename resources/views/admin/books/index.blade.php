<x-admin.layout title="Admin · Books">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Books</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Upload PDFs, set pricing, and manage inventory.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="px-5 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">Add book</a>
    </div>

    <div class="mt-6 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-black/5 dark:bg-white/10 text-ink-700 dark:text-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3">Title</th>
                        <th class="text-left px-4 py-3">Author</th>
                        <th class="text-left px-4 py-3">Prices (USD / EUR / INR)</th>
                        <th class="text-left px-4 py-3">Rating</th>
                        <th class="text-left px-4 py-3">Active</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $b)
                        <tr class="border-t border-black/5 dark:border-white/10">
                            <td class="px-4 py-3 font-medium">{{ $b->title }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $b->author }}</td>
                            <td class="px-4 py-3 text-xs leading-relaxed">
                                @php($u = $b->price_cents_usd ?? $b->price_cents)
                                @php($e = $b->price_cents_eur ?? $u)
                                @php($i = $b->price_cents_inr ?? $u)
                                <div>{{ \App\Services\Currency::format($u, 'USD') }}</div>
                                <div class="text-ink-500 dark:text-gray-400">{{ \App\Services\Currency::format($e, 'EUR') }}</div>
                                <div class="text-ink-500 dark:text-gray-400">{{ \App\Services\Currency::format($i, 'INR') }}</div>
                            </td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">★ {{ number_format((float)$b->rating_avg, 1) }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full {{ $b->is_active ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                                    {{ $b->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.books.edit', $b) }}" class="px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $books->links() }}</div>
</x-admin.layout>

