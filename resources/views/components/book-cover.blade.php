@props([
    'book',
    'size' => 'md', // md|sm
])

@php
    $title = $book->title ?? 'Untitled';
    $author = $book->author ?? '';
    $seed = crc32(($book->slug ?? $title) . '|' . $author);
    $palettes = [
        ['from' => 'from-amber-200', 'to' => 'to-rose-200', 'ring' => 'ring-amber-200/40'],
        ['from' => 'from-emerald-200', 'to' => 'to-sky-200', 'ring' => 'ring-sky-200/40'],
        ['from' => 'from-violet-200', 'to' => 'to-fuchsia-200', 'ring' => 'ring-fuchsia-200/40'],
        ['from' => 'from-orange-200', 'to' => 'to-lime-200', 'ring' => 'ring-lime-200/40'],
    ];
    $p = $palettes[$seed % count($palettes)];
    $pad = $size === 'sm' ? 'p-3' : 'p-4';
    $titleClass = $size === 'sm' ? 'text-sm' : 'text-base';
    $authorClass = $size === 'sm' ? 'text-[11px]' : 'text-xs';
@endphp

<div {{ $attributes->merge(['class' => "h-full w-full relative overflow-hidden rounded-2xl bg-gradient-to-br {$p['from']} {$p['to']} dark:from-white/10 dark:to-white/5 ring-1 ring-black/5 dark:ring-white/10"]) }}>
    @if(!empty($book->cover_url))
        <img src="{{ $book->cover_url }}" alt="{{ $title }}" class="h-full w-full object-cover" />
    @else
        <div class="{{ $pad }} h-full flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="h-8 w-8 rounded-xl bg-white/70 dark:bg-white/10 border border-black/5 dark:border-white/10"></div>
                <div class="text-[10px] tracking-wide uppercase text-ink-700/70 dark:text-gray-200/70">eBook</div>
            </div>

            <div>
                <div class="font-display {{ $titleClass }} leading-snug line-clamp-3 text-ink-900 dark:text-white">{{ $title }}</div>
                <div class="mt-1 {{ $authorClass }} text-ink-700/70 dark:text-gray-200/70 line-clamp-1">{{ $author }}</div>
            </div>

            <div class="flex items-center gap-2">
                <div class="h-1.5 flex-1 rounded-full bg-white/60 dark:bg-white/10"></div>
                <div class="h-1.5 w-10 rounded-full bg-white/60 dark:bg-white/10"></div>
            </div>
        </div>
    @endif
</div>

