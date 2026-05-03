<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingSubscription;
use App\Services\RazorpayPaymentLinkService;
use App\Services\ReadingAccessService;
use Carbon\Carbon;
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

    public function razorpayStart(Request $request, ReadingSubscription $readingSubscription, RazorpayPaymentLinkService $razorpay)
    {
        abort_unless($readingSubscription->user_id === $request->user()->id, 403);

        if ($readingSubscription->status === 'active') {
            return redirect()->route('subscriptions.index')->with('status', 'Already active.');
        }

        if ($readingSubscription->status !== 'pending') {
            return redirect()->route('subscriptions.index')->with('status', 'Invalid subscription state.');
        }

        if (! $razorpay->isConfigured()) {
            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', 'Razorpay is not configured.');
        }

        return $this->startRazorpay($request, $readingSubscription, $razorpay);
    }

    public function razorpayCallback(Request $request, ReadingSubscription $readingSubscription, RazorpayPaymentLinkService $razorpay)
    {
        abort_unless($readingSubscription->user_id === $request->user()->id, 403);

        if ($readingSubscription->status === 'active') {
            return redirect()->route('subscriptions.index')->with('status', 'Subscription already active.');
        }

        if (! $readingSubscription->razorpay_payment_link_id) {
            return redirect()->route('subscriptions.pending', $readingSubscription)->with('status', 'No payment session. Open Pay again.');
        }

        if (! $razorpay->isConfigured()) {
            return redirect()->route('subscriptions.pending', $readingSubscription);
        }

        try {
            $link = $razorpay->getPaymentLink($readingSubscription->razorpay_payment_link_id);
            $status = strtolower((string) ($link['status'] ?? ''));

            if ($status === 'paid') {
                $this->activateSubscription($readingSubscription);

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

    private function activateSubscription(ReadingSubscription $subscription): void
    {
        $subscription->forceFill([
            'status' => 'active',
            'paid_at' => now(),
            'starts_at' => now(),
            'ends_at' => $this->computeEndsAt($subscription),
        ])->save();
    }

    private function computeEndsAt(ReadingSubscription $subscription): Carbon
    {
        if ($subscription->plan_key === 'custom' && $subscription->custom_days) {
            return now()->addDays($subscription->custom_days);
        }

        $plan = config('reading_subscriptions.plans.'.$subscription->plan_key);
        $period = $plan['period'] ?? [];

        if (isset($period['months'])) {
            return now()->addMonths((int) $period['months']);
        }

        if (isset($period['years'])) {
            return now()->addYears((int) $period['years']);
        }

        return now()->addMonth();
    }

    private function startRazorpay(Request $request, ReadingSubscription $subscription, RazorpayPaymentLinkService $razorpay)
    {
        $currency = strtoupper((string) (config('razorpay.default_currency') ?: $subscription->currency));
        $amountSubunits = max(1, (int) $subscription->price_cents);

        $appUrl = rtrim((string) config('app.url'), '/');
        $callbackUrl = $appUrl.'/payment/razorpay/callback/subscription/'.$subscription->id;

        $user = $request->user();
        $customerName = $user->name ?: 'Customer';
        $customerEmail = $user->email ?: 'noreply@example.com';
        $customerContact = $this->randomContactDigits($currency);

        if ($subscription->razorpay_payment_link_id) {
            try {
                $existing = $razorpay->getPaymentLink($subscription->razorpay_payment_link_id);
                $status = strtolower((string) ($existing['status'] ?? ''));
                if ($status === 'paid') {
                    $this->activateSubscription($subscription);

                    return redirect()->route('subscriptions.index')->with('status', 'Subscription active.');
                }

                $shortUrl = isset($existing['short_url']) && is_string($existing['short_url']) ? $existing['short_url'] : null;
                if ($shortUrl && ! in_array($status, ['cancelled', 'expired'], true)) {
                    return redirect()->away($shortUrl);
                }
            } catch (\Throwable) {
                //
            }
        }

        $label = $subscription->plan_key === 'custom'
            ? 'Reading subscription ('.$subscription->custom_days.' days, up to '.$subscription->max_books.' books)'
            : 'Reading subscription ('.$subscription->plan_key.')';

        $body = [
            'amount' => $amountSubunits,
            'currency' => $currency,
            'description' => $label,
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
            'reference_id' => 'sub_'.$subscription->id,
            'notes' => [
                'reference_id' => 'sub_'.$subscription->id,
                'order_type' => 'reading_subscription',
                'source' => (string) config('razorpay.notes_source'),
            ],
        ];

        try {
            $response = $razorpay->createPaymentLink($body);
            $linkId = isset($response['id']) && is_string($response['id']) ? $response['id'] : null;
            $shortUrl = isset($response['short_url']) && is_string($response['short_url']) ? $response['short_url'] : null;

            if (! $linkId || ! $shortUrl) {
                return redirect()->route('subscriptions.index')->with('status', 'Could not start payment.');
            }

            $subscription->forceFill(['razorpay_payment_link_id' => $linkId])->save();

            return redirect()->away($shortUrl);
        } catch (\Throwable $e) {
            return redirect()->route('subscriptions.index')->with('status', $e->getMessage());
        }
    }

    private function randomContactDigits(string $currency): string
    {
        $currency = strtoupper($currency);

        if ($currency === 'INR') {
            $first = (string) random_int(6, 9);
            $rest = '';
            for ($i = 0; $i < 9; $i++) {
                $rest .= (string) random_int(0, 9);
            }

            return $first.$rest;
        }

        $first = (string) random_int(2, 9);
        $rest = '';
        for ($i = 0; $i < 9; $i++) {
            $rest .= (string) random_int(0, 9);
        }

        return $first.$rest;
    }
}
