<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $lowStockLimit = 5;

        $stats = [
            'today_sell' => Order::whereDate('created_at', $today)->where('order_status', '!=', 'cancelled')->count(),
            'today_income' => (float) Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total'),
            'today_profit' => OrderItem::paidProfit($today),
            'total_sell' => Order::where('order_status', '!=', 'cancelled')->count(),
            'total_income' => (float) Order::where('payment_status', 'paid')->sum('total'),
            'total_profit' => OrderItem::paidProfit(),
            'in_stock' => Product::where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', $lowStockLimit)->count(),
            'low_stock_limit' => $lowStockLimit,
            'low_stock_products' => Product::where('stock', '>', 0)
                ->where('stock', '<=', $lowStockLimit)
                ->with('category')
                ->orderBy('stock')
                ->take(10)
                ->get(),
            'out_of_stock_products' => Product::where('stock', '<=', 0)
                ->with('category')
                ->latest()
                ->take(10)
                ->get(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 1)->count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'recent_orders' => Order::with('items')->latest()->take(5)->get(),
            'total_users' => User::count(),
            'new_users_30d' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
