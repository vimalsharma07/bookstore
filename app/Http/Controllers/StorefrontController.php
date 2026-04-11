<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorefrontController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::query()
            ->withCount('books')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $featured = Book::query()
            ->where('is_active', true)
            ->orderByDesc('rating_avg')
            ->orderByDesc('purchases_count')
            ->limit(8)
            ->get();

        $heroStrip = $featured->take(4);
        $heroMarquee = $featured->take(8);

        $stats = [
            'books' => Book::query()->where('is_active', true)->count(),
            'reviews' => Review::query()->where('is_approved', true)->count(),
        ];

        $recommended = collect();
        $user = Auth::user();
        if ($user instanceof User) {
            $categoryIds = Category::query()
                ->whereHas('books.wishlistItems', fn ($q) => $q->where('user_id', $user->id))
                ->orWhereHas('books.libraryItems', fn ($q) => $q->where('user_id', $user->id))
                ->pluck('id')
                ->unique()
                ->values();

            $ownedBookIds = $user->libraryItems()->pluck('book_id');

            $recommended = Book::query()
                ->where('is_active', true)
                ->whereNotIn('id', $ownedBookIds)
                ->when($categoryIds->isNotEmpty(), fn ($q) => $q->whereHas('categories', fn ($cq) => $cq->whereIn('categories.id', $categoryIds)))
                ->orderByDesc('rating_avg')
                ->orderByDesc('purchases_count')
                ->limit(8)
                ->get();
        }

        return view('store.home', [
            'categories' => $categories,
            'featured' => $featured,
            'heroStrip' => $heroStrip,
            'heroMarquee' => $heroMarquee,
            'stats' => $stats,
            'recommended' => $recommended,
        ]);
    }
}
