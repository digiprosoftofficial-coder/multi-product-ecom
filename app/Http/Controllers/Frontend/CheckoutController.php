<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Rules\BangladeshPhone;
use App\Support\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data = \App\Support\Storefront::cartData();
        if (empty($data['cartItems'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($data['cartItems'] as $item) {
            $product = $item['product'];
            $itemTotal = taka($product->final_price) * $item['quantity'];
            $subtotal += $itemTotal;
            $cartItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'total' => $itemTotal,
                'variant' => $item['variant'] ?? null,
                'variant_id' => $item['variant_id'] ?? null,
                'variant_label' => $item['variant_label'] ?? null,
                'sku' => $item['sku'] ?? $product->sku,
                'max_stock' => $item['max_stock'] ?? max(1, (int) $product->stock),
            ];
        }

        $taxRate = (float) Setting::get('tax_rate', 0);
        $vatRate = (float) Setting::get('vat_rate', 0);

        $tax = taka(($subtotal * $taxRate) / 100);
        $vat = taka(($subtotal * $vatRate) / 100);
        $baseTotal = $subtotal + $tax + $vat;

        $shippingInside  = taka(Setting::get('shipping_inside_dhaka', 60));
        $shippingOutside = taka(Setting::get('shipping_outside_dhaka', 120));

        // Default zone: inside_dhaka
        $selectedZone   = old('delivery_zone', 'inside_dhaka');
        $shippingCost   = $selectedZone === 'outside_dhaka' ? $shippingOutside : $shippingInside;
        $total          = $baseTotal + $shippingCost;

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = View::exists("frontend.{$theme}.checkout") ? "frontend.{$theme}.checkout" : 'frontend.checkout.index';

        return view($view, compact('cartItems', 'subtotal', 'tax', 'vat', 'baseTotal', 'total',
            'shippingInside', 'shippingOutside', 'shippingCost', 'selectedZone'))
            ->with('paymentMethods', PaymentMethod::options());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => ['required', 'string', 'max:20', new BangladeshPhone],
            'shipping_address' => 'required|string',
            'delivery_zone' => 'required|in:inside_dhaka,outside_dhaka',
            'payment_method' => 'required|in:'.implode(',', PaymentMethod::values() ?: ['__none__']),
            'payment_reference' => [
                'nullable',
                'string',
                'max:100',
                Rule::requiredIf(fn () => PaymentMethod::isMobileWallet((string) $request->input('payment_method'))
                    && PaymentMethod::isEnabled((string) $request->input('payment_method'))),
            ],
            'payment_sender_phone' => [
                'nullable',
                'string',
                'max:20',
                new BangladeshPhone,
                Rule::requiredIf(fn () => PaymentMethod::isMobileWallet((string) $request->input('payment_method'))
                    && PaymentMethod::isEnabled((string) $request->input('payment_method'))),
            ],
            'notes' => 'nullable|string',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();
        try {
            $resolved = \App\Support\Storefront::cartData();
            $cartItems = [];
            $subtotal = 0;

            foreach ($resolved['cartItems'] as $row) {
                $product = Product::whereKey($row['product']->id)->lockForUpdate()->first();
                $variant = $row['variant_id']
                    ? \App\Models\ProductVariant::whereKey($row['variant_id'])->lockForUpdate()->first()
                    : null;

                $available = $variant ? $variant->stock : ($product?->stock ?? 0);
                if (! $product || ($row['variant_id'] && ! $variant) || $available < $row['quantity']) {
                    DB::rollBack();
                    $productName = $product ? $product->name : 'Unknown';

                    return back()->with('error', "Product '{$productName}' is out of stock or insufficient quantity.");
                }

                $unit = taka($product->final_price);
                $itemTotal = $unit * $row['quantity'];
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'variant_label' => $variant?->label($product->option1_name, $product->option2_name),
                    'sku' => $variant?->sku ?: $product->sku,
                    'quantity' => $row['quantity'],
                    'price' => $unit,
                    'total' => $itemTotal,
                ];
            }

            $taxRate = (float) Setting::get('tax_rate', 0);
            $vatRate = (float) Setting::get('vat_rate', 0);

            $tax = taka(($subtotal * $taxRate) / 100);
            $vat = taka(($subtotal * $vatRate) / 100);

            $shippingInside  = taka(Setting::get('shipping_inside_dhaka', 60));
            $shippingOutside = taka(Setting::get('shipping_outside_dhaka', 120));
            $shippingCost    = $validated['delivery_zone'] === 'outside_dhaka' ? $shippingOutside : $shippingInside;

            $total = $subtotal + $tax + $vat + $shippingCost;

            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => BangladeshPhone::normalize($validated['customer_phone']) ?? $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'delivery_zone'   => $validated['delivery_zone'],
                'shipping_cost'   => $shippingCost,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => PaymentMethod::isMobileWallet($validated['payment_method'])
                    ? ($validated['payment_reference'] ?? null)
                    : null,
                'payment_sender_phone' => PaymentMethod::isMobileWallet($validated['payment_method'])
                    ? (BangladeshPhone::normalize($validated['payment_sender_phone'] ?? null) ?? ($validated['payment_sender_phone'] ?? null))
                    : null,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'vat' => $vat,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_variant_id' => $item['variant']?->id,
                    'product_name' => $item['product']->name,
                    'product_sku' => $item['sku'],
                    'variant_label' => $item['variant_label'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'cost_price' => $item['product']->cost_price,
                    'total' => $item['total'],
                ]);

                if ($item['variant']) {
                    $item['variant']->decrement('stock', $item['quantity']);
                    $item['product']->decrement('stock', $item['quantity']);
                } else {
                    $item['product']->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();

            session(['cart' => [], 'placed_order_id' => $order->id]);

            try {
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            } catch (\Throwable $e) {
                Log::warning('Order confirmation email failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('checkout.thank-you', $order)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to place order', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function thankYou(Order $order)
    {
        if (! $order->isAccessibleToCurrentRequest()) {
            abort(403);
        }

        $order->load('items');
        $invoiceUrl = route('orders.invoice', $order);
        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = View::exists("frontend.{$theme}.checkout-thank-you")
            ? "frontend.{$theme}.checkout-thank-you"
            : 'frontend.checkout.thank-you';

        return view($view, compact('order', 'invoiceUrl'));
    }
}
