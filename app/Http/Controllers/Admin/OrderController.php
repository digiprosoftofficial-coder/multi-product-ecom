<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Rules\BangladeshPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

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

    public function edit(Order $order)
    {
        $order->load('items');

        return view('admin.orders.edit', [
            'order' => $order,
            'shippingInside' => taka(Setting::get('shipping_inside_dhaka', 60)),
            'shippingOutside' => taka(Setting::get('shipping_outside_dhaka', 120)),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => ['required', 'string', 'max:20', new BangladeshPhone],
            'shipping_address' => 'required|string|max:1000',
            'delivery_zone' => 'required|in:inside_dhaka,outside_dhaka',
            'shipping_cost' => 'required|numeric|min:0|max:999999',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1|max:999',
        ]);

        $order->load('items');
        $submitted = collect($validated['items'])->keyBy(fn ($row) => (int) $row['id']);
        $knownIds = $order->items->pluck('id')->map(fn ($id) => (int) $id)->sort()->values();
        $sentIds = $submitted->keys()->sort()->values();

        if ($knownIds->all() !== $sentIds->all()) {
            throw ValidationException::withMessages([
                'items' => 'Update every product already on this order.',
            ]);
        }

        DB::transaction(function () use ($order, $validated, $submitted) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $locked->load('items');

            $subtotal = 0;

            foreach ($locked->items as $item) {
                $newQty = (int) $submitted[(int) $item->id]['quantity'];
                $locked->adjustReservedStock($item, $newQty - (int) $item->quantity);
                $lineTotal = taka((float) $item->price * $newQty);
                $item->quantity = $newQty;
                $item->total = $lineTotal;
                $item->save();
                $subtotal += $lineTotal;
            }

            $oldSubtotal = (float) $locked->subtotal;
            if ($oldSubtotal > 0) {
                $tax = taka(((float) $locked->tax) * $subtotal / $oldSubtotal);
                $vat = taka(((float) $locked->vat) * $subtotal / $oldSubtotal);
            } else {
                $tax = taka(($subtotal * (float) Setting::get('tax_rate', 0)) / 100);
                $vat = taka(($subtotal * (float) Setting::get('vat_rate', 0)) / 100);
            }

            $shipping = taka($validated['shipping_cost']);

            $locked->fill([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?: null,
                'customer_phone' => BangladeshPhone::normalize($validated['customer_phone']) ?? $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'delivery_zone' => $validated['delivery_zone'],
                'shipping_cost' => $shipping,
                'notes' => $validated['notes'] ?: null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'vat' => $vat,
                'total' => $subtotal + $tax + $vat + $shipping,
            ])->save();
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated.');
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

    public function destroy(Order $order)
    {
        $number = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Order {$number} deleted.");
    }
}
