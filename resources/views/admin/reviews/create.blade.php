<x-admin.layout title="Admin · Add review">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Add review</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Shown on the book page when status is visible.</p>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 text-sm">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.reviews.store') }}" class="mt-6 space-y-5">
        @csrf
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6 space-y-4">
            <div>
                <label class="text-sm text-ink-500 dark:text-gray-300">Book</label>
                <select name="book_id" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5">
                    <option value="">Select book…</option>
                    @foreach($books as $b)
                        <option value="{{ $b->id }}" @selected(old('book_id') == $b->id)>{{ $b->title }}</option>
                    @endforeach
                </select>
                @error('book_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-sm text-ink-500 dark:text-gray-300">Reviewer name</label>
                <input name="reviewer_name" value="{{ old('reviewer_name') }}" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" placeholder="e.g. Jane D. Reader" />
                @error('reviewer_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-sm text-ink-500 dark:text-gray-300">Rating (1–5)</label>
                <input name="rating" type="number" min="1" max="5" value="{{ old('rating', 5) }}" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                @error('rating')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-sm text-ink-500 dark:text-gray-300">Title</label>
                <input name="title" value="{{ old('title') }}" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-sm text-ink-500 dark:text-gray-300">Review text</label>
                <textarea name="body" rows="5" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5">{{ old('body') }}</textarea>
                @error('body')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-sm text-ink-500 dark:text-gray-300">Visibility</label>
                <select name="is_approved" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5">
                    <option value="1" @selected(old('is_approved', '1') == '1' || old('is_approved', '1') === 1)>Visible on storefront</option>
                    <option value="0" @selected(old('is_approved') === '0' || old('is_approved') === 0)>Hidden</option>
                </select>
                @error('is_approved')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <button type="submit" class="px-5 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">Save review</button>
    </form>
</x-admin.layout>
