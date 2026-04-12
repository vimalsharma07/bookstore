<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with('user:id,name,email')
            ->latest()
            ->paginate(30);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user:id,name,email', 'items.book']);
        $paymentEmail = config('bookqueue.payment_email');

        return view('admin.orders.show', compact('order', 'paymentEmail'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->status === 'paid') {
            return back()->with('status', 'Order is already marked paid.');
        }

        if (! in_array($order->status, ['pending', 'payment_submitted'], true)) {
            return back()->with('status', 'Order cannot be confirmed from this status.');
        }

        $order->forceFill([
            'status' => 'paid',
            'paid_at' => now(),
        ])->save();

        CheckoutController::fulfillOrder($order);

        return back()->with('status', 'Payment confirmed. Library access granted.');
    }
}
