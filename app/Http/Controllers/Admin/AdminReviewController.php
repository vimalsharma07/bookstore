<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminReviewRequest;
use App\Http\Requests\Admin\UpdateAdminReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query()
            ->with(['book:id,title,slug', 'user:id,name'])
            ->when($request->filled('book_id'), fn ($q) => $q->where('book_id', $request->integer('book_id')))
            ->when($request->query('status') === 'approved', fn ($q) => $q->approved())
            ->when($request->query('status') === 'pending', fn ($q) => $q->pending())
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $books = Book::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.reviews.index', compact('reviews', 'books'));
    }

    public function create()
    {
        $books = Book::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.reviews.create', compact('books'));
    }

    public function store(StoreAdminReviewRequest $request)
    {
        $data = $request->validated();

        Review::create([
            'user_id' => null,
            'book_id' => $data['book_id'],
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'] ?? null,
            'reviewer_name' => $data['reviewer_name'],
            'is_approved' => (bool) (int) $data['is_approved'],
        ]);

        return redirect()->route('admin.reviews.index')->with('status', 'Review added.');
    }

    public function edit(Review $review)
    {
        $review->load(['book', 'user']);
        $books = Book::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.reviews.edit', compact('review', 'books'));
    }

    public function update(UpdateAdminReviewRequest $request, Review $review)
    {
        $data = $request->validated();

        $payload = [
            'book_id' => $data['book_id'],
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'] ?? null,
            'is_approved' => (bool) (int) $data['is_approved'],
        ];
        if ($review->user_id === null) {
            $payload['reviewer_name'] = $data['reviewer_name'] ?? null;
        }

        $review->update($payload);

        return redirect()->route('admin.reviews.index')->with('status', 'Review updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('status', 'Review deleted.');
    }
}
