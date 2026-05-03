<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ReadingSubscription;
use App\Services\RazorpayOrderService;
use App\Support\RazorpayPaymentSignature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RazorpayVerifyController extends Controller
{
    public function order(Request $request, RazorpayOrderService $orders): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $order = Order::query()->findOrFail($data['order_id']);
        abort_unless($order->user_id === $request->user()->id, 403);

        if ($order->status === 'paid') {
            return redirect()->route('library.index')->with('status', 'Order already paid.');
        }

        abort_unless($order->razorpay_order_id === $data['razorpay_order_id'], 422);

        if (! RazorpayPaymentSignature::verifyOrderPayment(
            $data['razorpay_order_id'],
            $data['razorpay_payment_id'],
            $data['razorpay_signature']
        )) {
            return redirect()->route('checkout.pending', $order)->with('status', 'Payment verification failed. Contact support if money was debited.');
        }

        try {
            $payment = $orders->fetchPayment($data['razorpay_payment_id']);
            $status = strtolower((string) ($payment['status'] ?? ''));
            if (! in_array($status, ['captured', 'authorized'], true)) {
                return redirect()->route('checkout.pending', $order)->with('status', 'Payment not completed.');
            }

            $paidAmount = (int) ($payment['amount'] ?? 0);
            if ($paidAmount !== (int) $order->total_cents) {
                return redirect()->route('checkout.pending', $order)->with('status', 'Payment amount mismatch.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('checkout.pending', $order)->with('status', 'Could not verify payment: '.$e->getMessage());
        }

        $order->forceFill([
            'status' => 'paid',
            'paid_at' => now(),
        ])->save();

        CheckoutController::fulfillOrder($order);

        return redirect()->route('library.index')->with('status', 'Payment successful. Your books are ready in My Library.');
    }

    public function subscription(Request $request, RazorpayOrderService $orders): RedirectResponse
    {
        $data = $request->validate([
            'reading_subscription_id' => ['required', 'integer', 'exists:reading_subscriptions,id'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $subscription = ReadingSubscription::query()->findOrFail($data['reading_subscription_id']);
        abort_unless($subscription->user_id === $request->user()->id, 403);

        if ($subscription->status === 'active') {
            return redirect()->route('subscriptions.index')->with('status', 'Subscription already active.');
        }

        abort_unless($subscription->razorpay_order_id === $data['razorpay_order_id'], 422);

        if (! RazorpayPaymentSignature::verifyOrderPayment(
            $data['razorpay_order_id'],
            $data['razorpay_payment_id'],
            $data['razorpay_signature']
        )) {
            return redirect()->route('subscriptions.pending', $subscription)->with('status', 'Payment verification failed.');
        }

        try {
            $payment = $orders->fetchPayment($data['razorpay_payment_id']);
            $status = strtolower((string) ($payment['status'] ?? ''));
            if (! in_array($status, ['captured', 'authorized'], true)) {
                return redirect()->route('subscriptions.pending', $subscription)->with('status', 'Payment not completed.');
            }

            $paidAmount = (int) ($payment['amount'] ?? 0);
            if ($paidAmount !== (int) $subscription->price_cents) {
                return redirect()->route('subscriptions.pending', $subscription)->with('status', 'Payment amount mismatch.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('subscriptions.pending', $subscription)->with('status', 'Could not verify payment: '.$e->getMessage());
        }

        $subscription->markPaidAndActivate();

        return redirect()->route('subscriptions.index')->with('status', 'Subscription active. Enjoy reading!');
    }
}
