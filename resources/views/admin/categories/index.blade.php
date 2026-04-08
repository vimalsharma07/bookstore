<x-admin.layout title="Admin · Categories">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Categories</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Genres shown on the homepage and filters.</p>
        </div>
    </div>

    <div class="mt-6 grid lg:grid-cols-2 gap-6">
        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <h2 class="font-display text-xl">Add category</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Name</label>
                    <input name="name" required class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Description</label>
                    <textarea name="description" rows="3" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5"></textarea>
                </div>
                <div>
                    <label class="text-sm text-ink-500 dark:text-gray-300">Sort order</label>
                    <input name="sort_order" value="0" class="mt-1 w-full px-4 py-3 rounded-2xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                </div>
                <button class="px-5 py-3 rounded-2xl bg-ink-900 text-white hover:bg-black transition">Create</button>
            </form>
        </div>

        <div class="rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
            <h2 class="font-display text-xl">Existing</h2>
            <div class="mt-4 space-y-3">
                @foreach($categories as $cat)
                    <form method="POST" action="{{ route('admin.categories.update', $cat) }}" class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/60 dark:bg-white/5 p-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                            <div class="sm:col-span-2">
                                <label class="text-xs text-ink-500 dark:text-gray-300">Name</label>
                                <input name="name" value="{{ $cat->name }}" class="mt-1 w-full px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                                <div class="text-xs text-ink-500 dark:text-gray-300 mt-1">Slug: <span class="font-mono">{{ $cat->slug }}</span></div>
                            </div>
                            <div>
                                <label class="text-xs text-ink-500 dark:text-gray-300">Sort</label>
                                <input name="sort_order" value="{{ $cat->sort_order }}" class="mt-1 w-full px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="text-xs text-ink-500 dark:text-gray-300">Description</label>
                            <textarea name="description" rows="2" class="mt-1 w-full px-3 py-2 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/5">{{ $cat->description }}</textarea>
                        </div>
                        <div class="mt-3">
                            <button class="px-4 py-2 rounded-xl bg-ink-900 text-white hover:bg-black transition text-sm">Save</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</x-admin.layout>

