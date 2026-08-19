<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items');

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', $term)
                    ->orWhere('customer_email', 'like', $term)
                    ->orWhere('customer_name', 'like', $term)
                    ->orWhereHas('items', function ($items) use ($term) {
                        $items->where('product_name', 'like', $term);
                    });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return response()->view('admin.orders.partials.results', compact('orders'));
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        $order->load('items');

        return view('invoices.print', [
            'order' => $order,
            'siteName' => site_name(),
            'logoUrl' => site_logo_url(),
            'backUrl' => route('admin.orders.show', $order),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,cancelled,refunded',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $locked->applyStatus($validated['order_status'], $validated['payment_status']);
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}
