<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LibraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $items = LibraryItem::query()
            ->where('user_id', $request->user()->id)
            ->with('book.categories')
            ->latest('purchased_at')
            ->paginate(12);

        return view('store.library.index', [
            'items' => $items,
        ]);
    }

    public function download(Request $request, Book $book)
    {
        $item = LibraryItem::query()
            ->where('user_id', $request->user()->id)
            ->where('book_id', $book->id)
            ->firstOrFail();

        $item->forceFill([
            'download_count' => $item->download_count + 1,
            'last_downloaded_at' => now(),
        ])->save();

        $path = $book->pdfAbsolutePath();
        if (! $path || ! file_exists($path)) {
            return redirect()
                ->route('library.index')
                ->with('status', 'This is a demo book (no PDF file uploaded yet). Ask admin to upload the PDF in Admin → Books.');
        }

        return response()->download($path, Str::slug($book->title).'.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
