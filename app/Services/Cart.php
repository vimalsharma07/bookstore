<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Collection;

class Cart
{
    private const SESSION_KEY = 'cart_items';

    /** @return array<int,int> bookId => qty */
    public static function raw(): array
    {
        $items = session()->get(self::SESSION_KEY, []);
        if (! is_array($items)) {
            return [];
        }

        $out = [];
        foreach ($items as $bookId => $qty) {
            $bookId = (int) $bookId;
            $qty = (int) $qty;
            if ($bookId > 0 && $qty > 0) {
                $out[$bookId] = $qty;
            }
        }

        return $out;
    }

    public static function count(): int
    {
        return array_sum(self::raw());
    }

    public static function add(int $bookId, int $qty = 1): void
    {
        $items = self::raw();
        $items[$bookId] = ($items[$bookId] ?? 0) + max(1, $qty);
        session()->put(self::SESSION_KEY, $items);
    }

    public static function setQty(int $bookId, int $qty): void
    {
        $items = self::raw();
        if ($qty <= 0) {
            unset($items[$bookId]);
        } else {
            $items[$bookId] = $qty;
        }
        session()->put(self::SESSION_KEY, $items);
    }

    public static function remove(int $bookId): void
    {
        $items = self::raw();
        unset($items[$bookId]);
        session()->put(self::SESSION_KEY, $items);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /** @return Collection<int,array{book:Book,qty:int,subtotal_cents:int}> */
    public static function lines(): Collection
    {
        $raw = self::raw();
        if ($raw === []) {
            return collect();
        }

        $books = Book::query()
            ->where('is_active', true)
            ->whereIn('id', array_keys($raw))
            ->get()
            ->keyBy('id');

        return collect($raw)->map(function (int $qty, int $bookId) use ($books) {
            /** @var Book|null $book */
            $book = $books->get($bookId);
            if (! $book) {
                return null;
            }

            $subtotal = $book->price_cents * $qty;
            return [
                'book' => $book,
                'qty' => $qty,
                'subtotal_cents' => $subtotal,
            ];
        })->filter()->values();
    }
}

