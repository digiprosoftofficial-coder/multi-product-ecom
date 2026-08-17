<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 1)->count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'recent_orders' => Order::latest()->take(5)->get(),
            'total_users' => User::count(),
            'new_users_30d' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

