<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'books' => Book::count(),
            'published_books' => Book::where('is_active', true)->count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'paid_orders' => Order::where('status', 'paid')->count(),
            'revenue_cents' => (int) Order::where('status', 'paid')->sum('total_cents'),
        ];

        $latestOrders = Order::query()
            ->with('user:id,name,email')
            ->latest()
            ->limit(8)
            ->get();

        $latestBooks = Book::query()
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestOrders', 'latestBooks'));
    }
}

