<x-admin.layout title="Admin · Reviews">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Reviews</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Add reviews for any book and control visibility.</p>
        </div>
        <a href="{{ route('admin.reviews.create') }}" class="px-5 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition text-center">Add review</a>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/40 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-900 dark:text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <form method="get" action="{{ route('admin.reviews.index') }}" class="mt-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="text-xs text-ink-500 dark:text-gray-300">Book</label>
            <select name="book_id" class="mt-1 block px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 text-sm">
                <option value="">All books</option>
                @foreach($books as $b)
                    <option value="{{ $b->id }}" @selected(request('book_id') == $b->id)>{{ $b->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-ink-500 dark:text-gray-300">Status</label>
            <select name="status" class="mt-1 block px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5 text-sm">
                <option value="" @selected(request('status') === '')>All</option>
                <option value="approved" @selected(request('status') === 'approved')>Visible</option>
                <option value="pending" @selected(request('status') === 'pending')>Hidden</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 text-sm">Filter</button>
        @if(request()->hasAny(['book_id', 'status']))
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 rounded-xl text-sm text-ink-500 hover:underline">Clear</a>
        @endif
    </form>

    <div class="mt-6 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-black/5 dark:bg-white/10 text-ink-700 dark:text-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3">Book</th>
                        <th class="text-left px-4 py-3">Reviewer</th>
                        <th class="text-left px-4 py-3">Rating</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Updated</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $r)
                        <tr class="border-t border-black/5 dark:border-white/10">
                            <td class="px-4 py-3">
                                <a href="{{ route('books.show', $r->book) }}" class="font-medium hover:underline">{{ $r->book->title }}</a>
                            </td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $r->reviewer_display_name }}</td>
                            <td class="px-4 py-3">★ {{ $r->rating }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full {{ $r->is_approved ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                                    {{ $r->is_approved ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $r->updated_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.reviews.edit', $r) }}" class="px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 text-sm">Edit</a>
                                <form action="{{ route('admin.reviews.destroy', $r) }}" method="post" class="inline" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-2 px-3 py-2 rounded-xl text-sm text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-ink-500 dark:text-gray-300">No reviews yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $reviews->links() }}</div>
</x-admin.layout>
