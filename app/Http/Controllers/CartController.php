<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\Cart;
use App\Services\Currency;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        $lines = Cart::lines();
        $totalCents = $lines->sum('subtotal_cents');
        $currency = Currency::current();

        return view('store.cart.show', [
            'lines' => $lines,
            'totalCents' => $totalCents,
            'currency' => $currency,
        ]);
    }

    public function add(Request $request, Book $book)
    {
        abort_unless($book->is_active, 404);

        $data = $request->validate([
            'qty' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        Cart::add($book->id, (int) ($data['qty'] ?? 1));

        return back()->with('status', 'Added to cart.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'qty' => ['required', 'array'],
            'qty.*' => ['nullable', 'integer', 'min:0', 'max:10'],
        ]);

        foreach ($data['qty'] as $bookId => $qty) {
            Cart::setQty((int) $bookId, (int) $qty);
        }

        return back()->with('status', 'Cart updated.');
    }

    public function remove(Book $book)
    {
        Cart::remove($book->id);
        return back()->with('status', 'Removed from cart.');
    }

    public function clear()
    {
        Cart::clear();
        return back()->with('status', 'Cart cleared.');
    }
}
