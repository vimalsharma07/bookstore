<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.orders.show', compact('order'));
    }
}
