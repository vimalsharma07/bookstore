<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LibraryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserBookActivity;
use App\Services\Cart;
use App\Services\Currency;
use App\Services\RazorpayPaymentLinkService;
use App\Support\PublicFileUpload;
use App\Support\RazorpayCustomerContact;
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
            'razorpayConfigured' => app(RazorpayPaymentLinkService::class)->isConfigured(),
        ]);
    }

    public function razorpayStart(Request $request, Order $order, RazorpayPaymentLinkService $razorpay)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->status === 'paid') {
            return redirect()->route('library.index')->with('status', 'This order is already complete.');
        }

        if (! in_array($order->status, ['pending', 'payment_submitted'], true)) {
            return redirect()->route('checkout.pending', $order)->with('status', 'This order cannot be paid online.');
        }

        if (! $razorpay->isConfigured()) {
            return redirect()->route('checkout.pending', $order)->with('status', 'Online payment is not configured. Add RAZORPAY_KEY and RAZORPAY_SECRET.');
        }

        $currency = strtoupper((string) (config('razorpay.default_currency') ?: $order->currency));
        $amountSubunits = max(1, (int) $order->total_cents);

        $appUrl = rtrim((string) config('app.url'), '/');
        $callbackUrl = $appUrl.'/payment/razorpay/callback/'.$order->id;

        $user = $request->user();
        $customerName = $user->name ?: 'Customer';
        $customerEmail = $order->email ?: $user->email ?: 'noreply@example.com';
        $customerContact = RazorpayCustomerContact::forPayment($user, $currency);

        if ($order->razorpay_payment_link_id) {
            try {
                $existing = $razorpay->getPaymentLink($order->razorpay_payment_link_id);
                $status = strtolower((string) ($existing['status'] ?? ''));
                if ($status === 'paid') {
                    return $this->finalizeRazorpayPaid($order);
                }

                $shortUrl = isset($existing['short_url']) && is_string($existing['short_url']) ? $existing['short_url'] : null;
                if ($shortUrl && ! in_array($status, ['cancelled', 'expired'], true)) {
                    return redirect()->away($shortUrl);
                }
            } catch (\Throwable) {
                //
            }
        }

        $body = [
            'amount' => $amountSubunits,
            'currency' => $currency,
            'description' => 'Payment for order #'.$order->id,
            'customer' => [
                'name' => $customerName,
                'email' => $customerEmail,
                'contact' => $customerContact,
            ],
            'notify' => [
                'sms' => false,
                'email' => false,
            ],
            'reminder_enable' => false,
            'callback_url' => $callbackUrl,
            'callback_method' => 'get',
            'reference_id' => (string) $order->id,
            'notes' => [
                'reference_id' => (string) $order->id,
                'order_type' => 'book',
                'source' => (string) config('razorpay.notes_source'),
            ],
        ];

        try {
            $response = $razorpay->createPaymentLink($body);
            $linkId = isset($response['id']) && is_string($response['id']) ? $response['id'] : null;
            $shortUrl = isset($response['short_url']) && is_string($response['short_url']) ? $response['short_url'] : null;

            if (! $linkId || ! $shortUrl) {
                return redirect()->route('checkout.pending', $order)->with('status', 'Could not start payment. Please try again.');
            }

            $order->forceFill(['razorpay_payment_link_id' => $linkId])->save();

            return redirect()->away($shortUrl);
        } catch (\Throwable $e) {
            return redirect()->route('checkout.pending', $order)->with('status', $e->getMessage());
        }
    }

    public function razorpayCallback(Request $request, Order $order, RazorpayPaymentLinkService $razorpay)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->status === 'paid') {
            return redirect()->route('library.index')->with('status', 'Payment already completed.');
        }

        if (! $order->razorpay_payment_link_id) {
            return redirect()->route('checkout.pending', $order)->with('status', 'No Razorpay payment link for this order. Click Pay with Razorpay again.');
        }

        if (! $razorpay->isConfigured()) {
            return redirect()->route('checkout.pending', $order)->with('status', 'Payment verification is unavailable.');
        }

        try {
            $link = $razorpay->getPaymentLink($order->razorpay_payment_link_id);
            $status = strtolower((string) ($link['status'] ?? ''));

            if ($status === 'paid') {
                return $this->finalizeRazorpayPaid($order);
            }

            $message = in_array($status, ['cancelled', 'expired'], true)
                ? 'This payment link is no longer active. Start checkout again.'
                : 'Payment not completed yet. If you already paid, wait a few seconds and refresh this page.';

            return redirect()->route('checkout.pending', $order)->with('status', $message);
        } catch (\Throwable $e) {
            return redirect()->route('checkout.pending', $order)->with('status', 'Could not verify payment: '.$e->getMessage());
        }
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

    private function finalizeRazorpayPaid(Order $order)
    {
        $order->refresh();

        if ($order->status === 'paid') {
            return redirect()->route('library.index')->with('status', 'Your books are in My Library.');
        }

        $order->forceFill([
            'status' => 'paid',
            'paid_at' => now(),
        ])->save();

        self::fulfillOrder($order);

        return redirect()->route('library.index')->with('status', 'Payment successful. Your books are ready in My Library.');
    }

}
