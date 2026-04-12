<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Support\PublicFileUpload;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    public function index()
    {
        $books = Book::query()->with('categories')->latest()->paginate(20);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'author' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:10000'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'price_eur' => ['required', 'numeric', 'min:0'],
            'price_inr' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:51200'],
            'preview_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $usd = (int) round(((float) $data['price_usd']) * 100);
        $eur = (int) round(((float) $data['price_eur']) * 100);
        $inr = (int) round(((float) $data['price_inr']) * 100);

        $book = Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'],
            'price_cents' => $usd,
            'currency' => 'USD',
            'price_cents_usd' => $usd,
            'price_cents_eur' => $eur,
            'price_cents_inr' => $inr,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'published_at' => $data['published_at'] ?? null,
            'pdf_path' => 'tmp',
        ]);

        if ($request->file('cover')) {
            $book->cover_path = PublicFileUpload::move($request->file('cover'), 'covers');
        }

        $book->pdf_path = PublicFileUpload::move($request->file('pdf'), 'books');

        if ($request->file('preview_pdf')) {
            $book->preview_pdf_path = PublicFileUpload::move($request->file('preview_pdf'), 'previews');
        }

        $book->save();

        $book->categories()->sync($data['categories'] ?? []);

        return redirect()->route('admin.books.index')->with('status', 'Book created.');
    }

    public function edit(Book $book)
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();
        $book->load('categories');

        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'author' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:10000'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'price_eur' => ['required', 'numeric', 'min:0'],
            'price_inr' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'preview_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $usd = (int) round(((float) $data['price_usd']) * 100);
        $eur = (int) round(((float) $data['price_eur']) * 100);
        $inr = (int) round(((float) $data['price_inr']) * 100);

        $book->update([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'],
            'price_cents' => $usd,
            'currency' => 'USD',
            'price_cents_usd' => $usd,
            'price_cents_eur' => $eur,
            'price_cents_inr' => $inr,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'published_at' => $data['published_at'] ?? null,
        ]);

        if ($request->file('cover')) {
            PublicFileUpload::deleteStored($book->cover_path);
            $book->cover_path = PublicFileUpload::move($request->file('cover'), 'covers');
        }

        if ($request->file('pdf')) {
            PublicFileUpload::deleteStored($book->pdf_path);
            $book->pdf_path = PublicFileUpload::move($request->file('pdf'), 'books');
        }

        if ($request->file('preview_pdf')) {
            PublicFileUpload::deleteStored($book->preview_pdf_path);
            $book->preview_pdf_path = PublicFileUpload::move($request->file('preview_pdf'), 'previews');
        }

        $book->save();
        $book->categories()->sync($data['categories'] ?? []);

        return redirect()->route('admin.books.index')->with('status', 'Book updated.');
    }
}
