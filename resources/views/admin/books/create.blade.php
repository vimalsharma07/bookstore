<x-admin.layout title="Admin · Add book">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Add book</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Upload a PDF (required) and optional cover/preview.</p>
        </div>
        <a href="{{ route('admin.books.index') }}" class="px-4 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-sm">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
        @csrf
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Title</label>
                    <input name="title" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Author</label>
                    <input name="author" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm font-medium text-ink-700 dark:text-gray-200">Prices (set each currency)</div>
                    <div class="mt-2 grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs text-ink-500 dark:text-gray-300">USD ($)</label>
                            <input name="price_usd" required placeholder="9.99" step="0.01" min="0" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                        </div>
                        <div>
                            <label class="text-xs text-ink-500 dark:text-gray-300">EUR (€)</label>
                            <input name="price_eur" required placeholder="9.49" step="0.01" min="0" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                        </div>
                        <div>
                            <label class="text-xs text-ink-500 dark:text-gray-300">INR (₹)</label>
                            <input name="price_inr" required placeholder="799.00" step="0.01" min="0" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm text-ink-500 dark:text-gray-300">Description</label>
                <textarea name="description" rows="6" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5"></textarea>
            </div>

            <div class="mt-4 grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Categories</label>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @foreach($categories as $cat)
                            <label class="text-sm px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="mr-2">
                                {{ $cat->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Active
                    </label>
                    <div>
                        <label class="text-sm text-ink-500 dark:text-gray-300">Published at</label>
                        <input type="date" name="published_at" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Cover image</label>
                    <input type="file" name="cover" accept="image/*" class="mt-2 w-full" />
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Book PDF (required)</label>
                    <input type="file" name="pdf" accept="application/pdf" required class="mt-2 w-full" />
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Preview PDF (optional)</label>
                    <input type="file" name="preview_pdf" accept="application/pdf" class="mt-2 w-full" />
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button class="px-6 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">Create</button>
            <a href="{{ route('admin.books.index') }}" class="px-6 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition">Cancel</a>
        </div>
    </form>
</x-admin.layout>

