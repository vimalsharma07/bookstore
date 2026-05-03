<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingSubscription;
use App\Services\RazorpayOrderService;
use App\Services\RazorpayPaymentLinkService;
use App\Services\ReadingAccessService;
use App\Support\RazorpayCustomerContact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReadingSubscriptionController extends Controller
{
    public function index()
    {
        return view('store.subscriptions.index', [
            'plans' => config('reading_subscriptions.plans'),
            'custom' => config('reading_subscriptions.custom'),
            'currency' => config('reading_subscriptions.currency'),
        ]);
    }

    public function store(Request $request, RazorpayPaymentLinkService $razorpay)
    {
        $customCfg = config('reading_subscriptions.custom');

        $data = $request->validate([
            'plan' => ['required', Rule::in(array_merge(array_keys(config('reading_subscriptions.plans')), ['custom']))],
            'custom_days' => [
                Rule::requiredIf(fn () => $request->input('plan') === 'custom'),
                'nullable',
                'integer',
                'min:'.$customCfg['min_days'],
                'max:'.$customCfg['max_days'],
            ],
        ]);

        $user = $request->user();

        if (! $razorpay->isConfigured()) {
            return redirect()->route('subscriptions.index')->with('status', 'Online payment is not configured (Razorpay keys).');
        }

        $currency = strtoupper((string) (config('razorpay.default_currency') ?: config('reading_subscriptions.currency')));

        if ($data['plan'] === 'custom') {
            $days = (int) $data['custom_days'];
            $priceCents = $days * (int) $customCfg['price_per_day_cents'];
            $subscription = ReadingSubscription::create([
                'user_id' => $user->id,
                'plan_key' => 'custom',
                'status' => 'pending',
                'price_cents' => max(1, $priceCents),
                'currency' => $currency,
                'custom_days' => $days,
                'max_books' => (int) $customCfg['max_books'],
            ]);
        } else {
            $plan = config('reading_subscriptions.plans.'.$data['plan']);
            if (! $plan) {
                return redirect()->route('subscriptions.index')->with('status', 'Invalid plan.');
            }
            $subscription = ReadingSubscription::create([
                'user_id' => $user->id,
                'plan_key' => $data['plan'],
                'status' => 'pending',
                'price_cents' => (int) $plan['price_cents'],
                'currency' => $currency,
                'custom_days' => null,
                'max_books' => null,
            ]);
        }

        return redirect()->route('subscriptions.pending', $subscription);
    }

    public function pending(Request $request, ReadingSubscription $readingSubscription, RazorpayPaymentLinkService $razorpay)
    {
        abort_unless($readingSubscription->user_id === $request->user()->id, 403);

        if ($readingSubscription->status === 'active') {
            return redirect()->route('subscriptions.index')->with('status', 'Your subscription is already active.');
        }

        if (! in_array($readingSubscription->status, ['pending'], true)) {
            return redirect()->route('subscriptions.index')->with('status', 'This subscription cannot be paid.');
        }

        return view('store.subscriptions.pending', [
            'subscription' => $readingSubscription,
            'razorpayConfigured' => $razorpay->isConfigured(),
        ]);
    }

    public function razorpayStart(Request $request, ReadingSubscription $readingSubscription, RazorpayOrderService $orderApi, RazorpayPaymentLinkService $linkService)
    {
        abort_unless($readingSubscription->user_id === $request->user()->id, 403);

        if ($readingSubscription->status === 'active') {
            return redirect()->route('subscriptions.index')->with('status', 'Already active.');
        }

        if ($readingSubscription->status !== 'pending') {
            return redirect()->route('subscriptions.index')->with('status', 'Invalid subscription state.');
        }

        if (! $linkService->isConfigured()) {
            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', 'Razorpay is not configured.');
        }

        $currency = strtoupper((string) (config('razorpay.default_currency') ?: $readingSubscription->currency));
        $amountSubunits = max(1, (int) $readingSubscription->price_cents);

        $user = $request->user();

        $description = $readingSubscription->plan_key === 'custom'
            ? 'Reading subscription ('.$readingSubscription->custom_days.' days)'
            : 'Reading subscription ('.$readingSubscription->plan_key.')';

        try {
            $rOrder = $orderApi->createOrder(
                $amountSubunits,
                $currency,
                'sub_'.$readingSubscription->id.'_'.time(),
                [
                    'reading_subscription_id' => (string) $readingSubscription->id,
                    'order_type' => 'reading_subscription',
                    'source' => (string) config('razorpay.notes_source'),
                ]
            );
        } catch (\Throwable $e) {
            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', $e->getMessage());
        }

        $razorpayOrderId = isset($rOrder['id']) && is_string($rOrder['id']) ? $rOrder['id'] : null;
        if (! $razorpayOrderId) {
            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', 'Could not start payment.');
        }

        $readingSubscription->forceFill(['razorpay_order_id' => $razorpayOrderId])->save();

        return view('store.payments.razorpay-checkout', [
            'title' => 'Pay subscription',
            'context' => 'subscription',
            'order' => null,
            'subscription' => $readingSubscription,
            'razorpayKey' => config('razorpay.key'),
            'razorpayOrderId' => $razorpayOrderId,
            'description' => $description,
            'prefillName' => $user->name ?: 'Customer',
            'prefillEmail' => $user->email ?: 'noreply@example.com',
            'prefillContact' => RazorpayCustomerContact::prefillContact($user, $currency),
            'verifyAction' => route('payment.razorpay.verify-subscription'),
            'backUrl' => route('subscriptions.pending', $readingSubscription),
        ]);
    }

    public function razorpayCallback(Request $request, ReadingSubscription $readingSubscription, RazorpayPaymentLinkService $razorpay)
    {
        abort_unless($readingSubscription->user_id === $request->user()->id, 403);

        if ($readingSubscription->status === 'active') {
            return redirect()->route('subscriptions.index')->with('status', 'Subscription already active.');
        }

        if (! $readingSubscription->razorpay_payment_link_id) {
            if ($readingSubscription->razorpay_order_id) {
                return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', 'Complete payment in the Razorpay window, or click Pay with Razorpay again.');
            }

            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', 'No payment session. Click Pay with Razorpay.');
        }

        if (! $razorpay->isConfigured()) {
            return redirect()->route('subscriptions.pending', $readingSubscription);
        }

        try {
            $link = $razorpay->getPaymentLink($readingSubscription->razorpay_payment_link_id);
            $status = strtolower((string) ($link['status'] ?? ''));

            if ($status === 'paid') {
                $readingSubscription->markPaidAndActivate();

                return redirect()->route('subscriptions.index')->with('status', 'Subscription active. Enjoy reading!');
            }

            $message = in_array($status, ['cancelled', 'expired'], true)
                ? 'Payment link expired. Start again from the subscription page.'
                : 'Payment not completed yet. Try again in a moment.';

            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', $message);
        } catch (\Throwable $e) {
            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', 'Could not verify payment: '.$e->getMessage());
        }
    }

    public function addBook(Request $request, Book $book, ReadingAccessService $access)
    {
        abort_unless($book->is_active, 404);

        $sub = $access->activeCustomSubscription($request->user());
        abort_unless($sub, 403);

        if ($sub->books()->count() >= (int) $sub->max_books) {
            return back()->with('status', 'You have reached the maximum of '.$sub->max_books.' books for this subscription.');
        }

        if ($sub->books()->where('books.id', $book->id)->exists()) {
            return back()->with('status', 'This book is already on your subscription list.');
        }

        $sub->books()->attach($book->id);

        return back()->with('status', 'Book added. You can download it from this page or My Library.');
    }

    public function removeBook(Request $request, Book $book, ReadingAccessService $access)
    {
        $sub = $access->activeCustomSubscription($request->user());
        abort_unless($sub, 403);

        $sub->books()->detach($book->id);

        return back()->with('status', 'Book removed from your subscription list.');
    }
}

