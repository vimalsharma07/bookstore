<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Models\UserBookActivity;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $min = $request->query('min');
        $max = $request->query('max');
        $sort = (string) $request->query('sort', 'popular');

        $books = Book::query()
            ->where('is_active', true)
            ->with('categories')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                        ->orWhere('author', 'like', "%{$q}%");
                });
            })
            ->when($category !== '', fn ($query) => $query->whereHas('categories', fn ($cq) => $cq->where('slug', $category)))
            ->when(is_numeric($min), fn ($query) => $query->where('price_cents', '>=', (int) round(((float) $min) * 100)))
            ->when(is_numeric($max), fn ($query) => $query->where('price_cents', '<=', (int) round(((float) $max) * 100)))
            ->when($sort === 'new', fn ($query) => $query->orderByDesc('published_at'))
            ->when($sort === 'rating', fn ($query) => $query->orderByDesc('rating_avg'))
            ->when($sort === 'price_low', fn ($query) => $query->orderBy('price_cents'))
            ->when($sort === 'price_high', fn ($query) => $query->orderByDesc('price_cents'))
            ->when($sort === 'popular', fn ($query) => $query->orderByDesc('purchases_count'))
            ->paginate(12)
            ->withQueryString();

        $categories = \App\Models\Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['name', 'slug']);

        return view('store.books.index', [
            'books' => $books,
            'categories' => $categories,
            'filters' => [
                'q' => $q,
                'category' => $category,
                'min' => $min,
                'max' => $max,
                'sort' => $sort,
            ],
        ]);
    }

    public function show(Request $request, Book $book)
    {
        abort_unless($book->is_active, 404);

        $book->load(['categories']);
        $reviews = $book->reviews()
            ->where('is_approved', true)
            ->latest()
            ->with('user:id,name')
            ->limit(12)
            ->get();

        $user = Auth::user();
        $inWishlist = false;
        $owned = false;
        if ($user) {
            $inWishlist = WishlistItem::query()->where('user_id', $user->id)->where('book_id', $book->id)->exists();
            $owned = \App\Models\LibraryItem::query()->where('user_id', $user->id)->where('book_id', $book->id)->exists();

            UserBookActivity::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'action' => 'view',
                'weight' => 1,
                'occurred_at' => now(),
            ]);
        }

        $recommended = Book::query()
            ->where('is_active', true)
            ->where('id', '!=', $book->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $book->categories->pluck('id')))
            ->orderByDesc('rating_avg')
            ->orderByDesc('purchases_count')
            ->limit(6)
            ->get();

        return view('store.books.show', [
            'book' => $book,
            'reviews' => $reviews,
            'inWishlist' => $inWishlist,
            'owned' => $owned,
            'recommended' => $recommended,
        ]);
    }

    public function preview(Book $book)
    {
        abort_unless($book->is_active, 404);
        abort_if(! $book->preview_pdf_path, 404);

        $path = public_path('storage/'.$book->preview_pdf_path);
        abort_unless(file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function toggleWishlist(Request $request, Book $book)
    {
        $user = $request->user();

        $existing = WishlistItem::query()
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('status', 'Removed from wishlist.');
        }

        WishlistItem::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        UserBookActivity::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'action' => 'wishlist',
            'weight' => 2,
            'occurred_at' => now(),
        ]);

        return back()->with('status', 'Added to wishlist.');
    }

    public function storeReview(Request $request, Book $book)
    {
        $user = $request->user();

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            [
                'rating' => $data['rating'],
                'title' => $data['title'] ?? null,
                'body' => $data['body'] ?? null,
                'is_approved' => true,
            ]
        );

        $this->recalculateBookRating($book);

        return back()->with('status', 'Thanks for your review.');
    }

    private function recalculateBookRating(Book $book): void
    {
        $stats = Review::query()
            ->where('book_id', $book->id)
            ->where('is_approved', true)
            ->selectRaw('COUNT(*) as c, AVG(rating) as a')
            ->first();

        $book->reviews_count = (int) ($stats->c ?? 0);
        $book->rating_avg = (float) ($stats->a ?? 0);
        $book->save();
    }
}
