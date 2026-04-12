<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LibraryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserBookActivity;
use App\Services\Cart;
use App\Services\Currency;
use App\Support\PublicFileUpload;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function start(Request $request, Book $book)
    {
        abort_unless($book->is_active, 404);

        $user = $request->user();

        if (LibraryItem::query()->where('user_id', $user->id)->where('book_id', $book->id)->exists()) {
            return redirect()->route('library.index')->with('status', 'You already own this book.');
        }

        $ccy = Currency::current();
        $unit = $book->priceCentsIn($ccy);

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'currency' => $ccy,
            'total_cents' => $unit,
            'email' => $user->email,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'unit_price_cents' => $unit,
            'quantity' => 1,
            'title_snapshot' => $book->title,
        ]);

        return redirect()->route('checkout.pending', $order);
    }

    public function startFromCart(Request $request)
    {
        $user = $request->user();

        $lines = Cart::lines();
        if ($lines->isEmpty()) {
            return redirect()->route('cart.show')->with('status', 'Your cart is empty.');
        }

        $ccy = Currency::current();
        $totalCents = 0;

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'currency' => $ccy,
            'total_cents' => 0,
            'email' => $user->email,
        ]);

        foreach ($lines as $line) {
            /** @var Book $book */
            $book = $line['book'];
            $qty = (int) $line['qty'];
            $unit = $book->priceCentsIn($ccy);
            $totalCents += $unit * $qty;

            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'unit_price_cents' => $unit,
                'quantity' => $qty,
                'title_snapshot' => $book->title,
            ]);
        }

        $order->update(['total_cents' => $totalCents]);

        Cart::clear();

        return redirect()->route('checkout.pending', $order);
    }

    public function pending(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items.book');

        return view('store.checkout.pending', [
            'order' => $order,
            'paymentEmail' => config('bookqueue.payment_email'),
        ]);
    }

    public function submitPaymentProof(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->status === 'paid') {
            return redirect()->route('library.index')->with('status', 'This order is already complete.');
        }

        $request->validate([
            'payment_proof' => ['required', 'image', 'max:5120', 'mimes:jpeg,jpg,png,webp'],
        ]);

        if ($order->payment_proof_path) {
            PublicFileUpload::deletePublic($order->payment_proof_path);
        }

        $path = PublicFileUpload::movePaymentProof($request->file('payment_proof'), $order->id);

        $order->forceFill([
            'payment_proof_path' => $path,
            'payment_proof_submitted_at' => now(),
            'status' => 'payment_submitted',
        ])->save();

        return back()->with('status', 'Payment proof received. We will confirm your order shortly—usually within 5 minutes during business hours.');
    }

    public static function fulfillOrder(Order $order): void
    {
        $order->load('items.book');

        foreach ($order->items as $item) {
            $book = $item->book;
            if (! $book) {
                continue;
            }

            LibraryItem::firstOrCreate(
                ['user_id' => $order->user_id, 'book_id' => $book->id],
                [
                    'order_id' => $order->id,
                    'purchased_at' => now(),
                ]
            );

            $book->increment('purchases_count', max(1, (int) $item->quantity));

            UserBookActivity::create([
                'user_id' => $order->user_id,
                'book_id' => $book->id,
                'action' => 'purchase',
                'weight' => 5,
                'occurred_at' => now(),
            ]);
        }
    }
}
