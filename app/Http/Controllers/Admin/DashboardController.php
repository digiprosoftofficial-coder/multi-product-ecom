<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ];

        $notifications = Auth::user()
            ? Auth::user()->notifications()->latest()->take(10)->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'notifications'));
    }
}

