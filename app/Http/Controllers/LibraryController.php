<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LibraryItem;
use App\Services\ReadingAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    public function index(Request $request, ReadingAccessService $readingAccess)
    {
        $user = $request->user();

        $items = LibraryItem::query()
            ->where('user_id', $user->id)
            ->with('book.categories')
            ->latest('purchased_at')
            ->paginate(12);

        $readingUnlimited = $readingAccess->activeUnlimitedSubscription($user);
        $readingCustom = $readingAccess->activeCustomSubscription($user);

        return view('store.library.index', [
            'items' => $items,
            'readingUnlimited' => $readingUnlimited,
            'readingCustom' => $readingCustom,
        ]);
    }

    public function download(Request $request, Book $book, ReadingAccessService $readingAccess)
    {
        $user = $request->user();

        $item = LibraryItem::query()
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if ($item) {
            $item->forceFill([
                'download_count' => $item->download_count + 1,
                'last_downloaded_at' => now(),
            ])->save();
        } elseif (! $readingAccess->canAccessBook($user, $book)) {
            abort(403);
        }

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
