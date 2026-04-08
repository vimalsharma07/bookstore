<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:51200'],
            'preview_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $book = Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'],
            'price_cents' => (int) round(((float) $data['price']) * 100),
            'currency' => strtoupper($data['currency']),
            'is_active' => (bool) ($data['is_active'] ?? true),
            'published_at' => $data['published_at'] ?? null,
            'pdf_path' => 'tmp', // replaced below
        ]);

        if ($request->file('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $book->cover_path = $coverPath;
        }

        $pdfPath = $request->file('pdf')->store('books', 'local');
        $book->pdf_path = $pdfPath;

        if ($request->file('preview_pdf')) {
            $previewPath = $request->file('preview_pdf')->store('previews', 'public');
            $book->preview_pdf_path = $previewPath;
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
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'preview_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $book->update([
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'],
            'price_cents' => (int) round(((float) $data['price']) * 100),
            'currency' => strtoupper($data['currency']),
            'is_active' => (bool) ($data['is_active'] ?? false),
            'published_at' => $data['published_at'] ?? null,
        ]);

        if ($request->file('cover')) {
            if ($book->cover_path) {
                Storage::disk('public')->delete($book->cover_path);
            }
            $book->cover_path = $request->file('cover')->store('covers', 'public');
        }

        if ($request->file('pdf')) {
            Storage::disk('local')->delete($book->pdf_path);
            $book->pdf_path = $request->file('pdf')->store('books', 'local');
        }

        if ($request->file('preview_pdf')) {
            if ($book->preview_pdf_path) {
                Storage::disk('public')->delete($book->preview_pdf_path);
            }
            $book->preview_pdf_path = $request->file('preview_pdf')->store('previews', 'public');
        }

        $book->save();
        $book->categories()->sync($data['categories'] ?? []);

        return redirect()->route('admin.books.index')->with('status', 'Book updated.');
    }
}
