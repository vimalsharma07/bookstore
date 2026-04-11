<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\Review;

class ReviewObserver
{
    public function saved(Review $review): void
    {
        if ($review->wasChanged('book_id') && $review->getOriginal('book_id')) {
            Book::query()->find($review->getOriginal('book_id'))?->refreshReviewAggregates();
        }

        $review->book->refreshReviewAggregates();
    }

    public function deleted(Review $review): void
    {
        Book::query()->find($review->book_id)?->refreshReviewAggregates();
    }
}
