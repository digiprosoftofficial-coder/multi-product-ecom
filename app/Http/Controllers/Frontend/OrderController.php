<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = View::exists("frontend.{$theme}.orders.index") ? "frontend.{$theme}.orders.index" : 'frontend.orders.index';

        return view($view, compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = View::exists("frontend.{$theme}.orders.show") ? "frontend.{$theme}.orders.show" : 'frontend.orders.show';

        return view($view, compact('order'));
    }

    public function invoice(Order $order)
    {
        if (! $order->isAccessibleToCurrentRequest()) {
            abort(403);
        }

        $order->load('items');

        if (Auth::check() && $order->user_id === Auth::id()) {
            $backUrl = route('orders.show', $order);
        } elseif ((int) session('placed_order_id') === (int) $order->id) {
            $backUrl = route('checkout.thank-you', $order);
        } else {
            $backUrl = route('home');
        }

        return view('invoices.print', [
            'order' => $order,
            'siteName' => site_name(),
            'logoUrl' => site_logo_url(),
            'backUrl' => $backUrl,
        ]);
    }
}
