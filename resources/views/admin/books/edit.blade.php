<x-admin.layout title="Admin · Edit book">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Edit book</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">{{ $book->title }}</p>
        </div>
        <a href="{{ route('admin.books.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" class="mt-6 space-y-5">
        @csrf
        @method('PUT')

        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Title</label>
                    <input name="title" required value="{{ $book->title }}" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Author</label>
                    <input name="author" required value="{{ $book->author }}" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm font-medium text-ink-700 dark:text-gray-200">Prices (USD / EUR / INR)</div>
                    <div class="mt-2 grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs text-ink-500 dark:text-gray-300">USD ($)</label>
                            <input name="price_usd" required value="{{ number_format(($book->price_cents_usd ?? $book->price_cents) / 100, 2, '.', '') }}" step="0.01" min="0" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                        </div>
                        <div>
                            <label class="text-xs text-ink-500 dark:text-gray-300">EUR (€)</label>
                            <input name="price_eur" required value="{{ number_format(($book->price_cents_eur ?? $book->price_cents_usd ?? $book->price_cents) / 100, 2, '.', '') }}" step="0.01" min="0" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                        </div>
                        <div>
                            <label class="text-xs text-ink-500 dark:text-gray-300">INR (₹)</label>
                            <input name="price_inr" required value="{{ number_format(($book->price_cents_inr ?? $book->price_cents_usd ?? $book->price_cents) / 100, 2, '.', '') }}" step="0.01" min="0" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm text-ink-500 dark:text-gray-300">Description</label>
                <textarea name="description" rows="6" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5">{{ $book->description }}</textarea>
            </div>

            <div class="mt-4 grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Categories</label>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @foreach($categories as $cat)
                            <label class="text-sm px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="mr-2"
                                    @checked($book->categories->contains('id', $cat->id))>
                                {{ $cat->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_active" value="1" @checked($book->is_active)>
                        Active
                    </label>
                    <div>
                        <label class="text-sm text-ink-500 dark:text-gray-300">Published at</label>
                        <input type="date" name="published_at" value="{{ optional($book->published_at)->format('Y-m-d') }}"
                               class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Cover image</label>
                    @if($book->cover_url)
                        <img src="{{ $book->cover_url }}" class="mt-2 h-32 rounded-2xl object-cover border border-black/5 dark:border-white/10" />
                    @endif
                    <input type="file" name="cover" accept="image/*" class="mt-2 w-full" />
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Replace book PDF</label>
                    <input type="file" name="pdf" accept="application/pdf" class="mt-2 w-full" />
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Replace preview PDF</label>
                    <input type="file" name="preview_pdf" accept="application/pdf" class="mt-2 w-full" />
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button class="px-6 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">Save</button>
            <a href="{{ route('admin.books.index') }}" class="px-6 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Cancel</a>
        </div>
    </form>
</x-admin.layout>

