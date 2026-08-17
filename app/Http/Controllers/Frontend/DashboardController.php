<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.dashboard") ? "frontend.{$theme}.dashboard" : 'frontend.dashboard';
        return view($view, compact('user', 'recentOrders'));
    }
}

