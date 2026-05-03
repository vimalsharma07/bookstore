<?php

namespace App\Services;

use App\Models\Book;
use App\Models\LibraryItem;
use App\Models\ReadingSubscription;
use App\Models\User;
use Illuminate\Support\Collection;

class ReadingAccessService
{
    /**
     * @return Collection<int, ReadingSubscription>
     */
    public function activeSubscriptions(User $user): Collection
    {
        return ReadingSubscription::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '>', now())
            ->with(['books' => fn ($q) => $q->select('books.id', 'books.slug', 'books.title', 'books.author')])
            ->get();
    }

    public function canAccessBook(User $user, Book $book): bool
    {
        if (LibraryItem::query()->where('user_id', $user->id)->where('book_id', $book->id)->exists()) {
            return true;
        }

        $subs = $this->activeSubscriptions($user);

        foreach ($subs as $sub) {
            if ($sub->isUnlimited()) {
                return true;
            }
        }

        foreach ($subs as $sub) {
            if (! $sub->isUnlimited() && $sub->books->contains('id', $book->id)) {
                return true;
            }
        }

        return false;
    }

    public function activeCustomSubscription(User $user): ?ReadingSubscription
    {
        return $this->activeSubscriptions($user)->first(fn (ReadingSubscription $s) => ! $s->isUnlimited());
    }

    public function activeUnlimitedSubscription(User $user): ?ReadingSubscription
    {
        return $this->activeSubscriptions($user)->first(fn (ReadingSubscription $s) => $s->isUnlimited());
    }
}
