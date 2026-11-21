<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Rules\BangladeshPhone;
use App\Support\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function create()
    {
        $catalog = Product::query()
            ->where('status', 1)
            ->with(['variants' => fn ($query) => $query->orderBy('id')])
            ->orderBy('name')
            ->get()
            ->map(function (Product $product) {
                $option1 = $product->getRawOriginal('option1_name') ?: 'Size';
                $option2 = $product->getRawOriginal('option2_name') ?: 'Color';

                return [
                    'id' => $product->id,
                    'name' => (string) $product->getRawOriginal('name'),
                    'sku' => $product->sku,
                    'price' => taka($product->final_price),
                    'stock' => (int) $product->stock,
                    'free_delivery' => (bool) $product->free_delivery,
                    'has_variants' => $product->hasVariants(),
                    'variants' => $product->variants->map(fn (ProductVariant $variant) => [
                        'id' => $variant->id,
                        'label' => $variant->label($option1, $option2) ?: 'Option',
                        'sku' => $variant->sku,
                        'stock' => (int) $variant->stock,
                    ])->values(),
                ];
            })
            ->values();

        return view('admin.orders.create', [
            'catalog' => $catalog,
            'methods' => PaymentMethod::all(),
            'shippingInside' => taka(Setting::get('shipping_inside_dhaka', 60)),
            'shippingOutside' => taka(Setting::get('shipping_outside_dhaka', 120)),
            'taxRate' => (float) Setting::get('tax_rate', 0),
            'vatRate' => (float) Setting::get('vat_rate', 0),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => ['required', 'string', 'max:20', new BangladeshPhone],
            'shipping_address' => 'required|string|max:1000',
            'delivery_zone' => 'required|in:inside_dhaka,outside_dhaka',
            'shipping_cost' => 'required|numeric|min:0|max:999999',
            'payment_method' => 'required|in:'.implode(',', PaymentMethod::allValues()),
            'payment_status' => 'required|in:pending,paid',
            'payment_reference' => 'nullable|string|max:100',
            'payment_sender_phone' => ['nullable', 'string', 'max:20', new BangladeshPhone],
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.quantity' => 'required|integer|min:1|max:999',
            'items.*.price' => 'required|numeric|min:0|max:999999',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $lines = [];

            foreach ($validated['items'] as $index => $row) {
                $product = Product::whereKey($row['product_id'])->lockForUpdate()->first();
                $variantId = $row['variant_id'] ?? null;
                $variant = $variantId
                    ? ProductVariant::whereKey($variantId)->lockForUpdate()->first()
                    : null;

                if (! $product || (int) $product->status !== 1) {
                    throw ValidationException::withMessages([
                        "items.$index.product_id" => 'Choose an active product.',
                    ]);
                }

                if ($product->hasVariants()) {
                    if (! $variant || (int) $variant->product_id !== (int) $product->id) {
                        throw ValidationException::withMessages([
                            "items.$index.variant_id" => 'Choose an option for '.$product->getRawOriginal('name').'.',
                        ]);
                    }
                } elseif ($variant) {
                    $variant = null;
                }

                $quantity = (int) $row['quantity'];
                $available = $variant ? (int) $variant->stock : (int) $product->stock;
                $name = (string) $product->getRawOriginal('name');

                if ($available < $quantity || ($variant && (int) $product->stock < $quantity)) {
                    throw ValidationException::withMessages([
                        "items.$index.quantity" => "Not enough stock for {$name}.",
                    ]);
                }

                $unit = taka($row['price']);
                $lineTotal = $unit * $quantity;
                $subtotal += $lineTotal;
                $option1 = $product->getRawOriginal('option1_name') ?: 'Size';
                $option2 = $product->getRawOriginal('option2_name') ?: 'Color';

                $lines[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'name' => $name,
                    'sku' => $variant?->sku ?: $product->sku,
                    'variant_label' => $variant?->label($option1, $option2),
                    'quantity' => $quantity,
                    'price' => $unit,
                    'total' => $lineTotal,
                ];
            }

            $tax = taka(($subtotal * (float) Setting::get('tax_rate', 0)) / 100);
            $vat = taka(($subtotal * (float) Setting::get('vat_rate', 0)) / 100);
            $shipping = taka($validated['shipping_cost']);
            $wallet = PaymentMethod::isMobileWallet($validated['payment_method']);

            $order = Order::create([
                'user_id' => null,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?: null,
                'customer_phone' => BangladeshPhone::normalize($validated['customer_phone']) ?? $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'delivery_zone' => $validated['delivery_zone'],
                'shipping_cost' => $shipping,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $wallet ? ($validated['payment_reference'] ?? null) : null,
                'payment_sender_phone' => $wallet
                    ? (BangladeshPhone::normalize($validated['payment_sender_phone'] ?? null) ?? ($validated['payment_sender_phone'] ?? null))
                    : null,
                'payment_status' => $validated['payment_status'],
                'order_status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'vat' => $vat,
                'total' => $subtotal + $tax + $vat + $shipping,
                'notes' => $validated['notes'] ?: null,
            ]);

            foreach ($lines as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product']->id,
                    'product_variant_id' => $line['variant']?->id,
                    'product_name' => $line['name'],
                    'product_sku' => $line['sku'],
                    'variant_label' => $line['variant_label'],
                    'quantity' => $line['quantity'],
                    'price' => $line['price'],
                    'cost_price' => $line['product']->cost_price,
                    'total' => $line['total'],
                ]);

                if ($line['variant']) {
                    $line['variant']->decrement('stock', $line['quantity']);
                }
                $line['product']->decrement('stock', $line['quantity']);
            }

            return $order;
        });

        if ($order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            } catch (\Throwable $e) {
                Log::warning('Manual sale confirmation email failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Sale saved as '.$order->order_number.'.');
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
