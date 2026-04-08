<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LibraryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart;
use App\Services\TestPaymentGateway;
use App\Models\UserBookActivity;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function start(Request $request, Book $book)
    {
        abort_unless($book->is_active, 404);

        $user = $request->user();

        // Already owned? send to library.
        if (\App\Models\LibraryItem::query()->where('user_id', $user->id)->where('book_id', $book->id)->exists()) {
            return redirect()->route('library.index')->with('status', 'You already own this book.');
        }

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'currency' => $book->currency,
            'total_cents' => $book->price_cents,
            'email' => $user->email,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'unit_price_cents' => $book->price_cents,
            'quantity' => 1,
            'title_snapshot' => $book->title,
        ]);

        // Payment gateway integration point:
        // - Create a payment session/redirect using your provider
        // - When payment is confirmed, mark order as paid and create a LibraryItem for the user+book

        return redirect()->route('checkout.pending', $order);
    }

    public function startFromCart(Request $request)
    {
        $user = $request->user();

        $lines = Cart::lines();
        if ($lines->isEmpty()) {
            return redirect()->route('cart.show')->with('status', 'Your cart is empty.');
        }

        // For simplicity we assume one currency across cart.
        $currency = $lines->first()['book']->currency;
        $totalCents = (int) $lines->sum('subtotal_cents');

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'currency' => $currency,
            'total_cents' => $totalCents,
            'email' => $user->email,
        ]);

        foreach ($lines as $line) {
            /** @var \App\Models\Book $book */
            $book = $line['book'];
            $qty = (int) $line['qty'];

            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'unit_price_cents' => $book->price_cents,
                'quantity' => $qty,
                'title_snapshot' => $book->title,
            ]);
        }

        Cart::clear();

        return redirect()->route('checkout.pending', $order);
    }

    public function pending(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items.book');

        return view('store.checkout.pending', [
            'order' => $order,
        ]);
    }

    public function pay(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items.book');

        if ($order->status === 'paid') {
            return redirect()->route('library.index')->with('status', 'Order already paid. Your books are in My Library.');
        }

        $data = $request->validate([
            'card_number' => ['required', 'string', 'max:30'],
            'expiry' => ['required', 'string', 'max:7'],
            'cvc' => ['required', 'string', 'max:4'],
        ]);

        $result = TestPaymentGateway::charge($data['card_number'], $data['expiry'], $data['cvc']);

        if (! $result['ok']) {
            $order->update(['status' => 'failed']);
            return back()->with('status', $result['message']);
        }

        // Mark order as paid and fulfill.
        $order->forceFill([
            'status' => 'paid',
            'paid_at' => now(),
        ])->save();

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

        return redirect()->route('library.index')->with('status', 'Payment successful. Your books are now available in My Library.');
    }
}
