<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if ($product) {
                $itemTotal = $product->final_price * $item['quantity'];
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ];
            }
        }

        $taxRate = (float) Setting::get('tax_rate', 0);
        $vatRate = (float) Setting::get('vat_rate', 0);

        $tax = ($subtotal * $taxRate) / 100;
        $vat = ($subtotal * $vatRate) / 100;
        $total = $subtotal + $tax + $vat;

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = View::exists("frontend.{$theme}.checkout") ? "frontend.{$theme}.checkout" : 'frontend.checkout.index';

        return view($view, compact('cartItems', 'subtotal', 'tax', 'vat', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();
        try {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $cartItems = [];
            $subtotal = 0;

            foreach ($cart as $productId => $item) {
                $product = $products->get($productId);
                if (! $product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    $productName = $product ? $product->name : 'Unknown';

                    return back()->with('error', "Product '{$productName}' is out of stock or insufficient quantity.");
                }

                $itemTotal = $product->final_price * $item['quantity'];
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->final_price,
                    'total' => $itemTotal,
                ];
            }

            $taxRate = (float) Setting::get('tax_rate', 0);
            $vatRate = (float) Setting::get('vat_rate', 0);

            $tax = ($subtotal * $taxRate) / 100;
            $vat = ($subtotal * $vatRate) / 100;
            $total = $subtotal + $tax + $vat;

            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => 'cash_on_delivery',
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
                    'product_name' => $item['product']->name,
                    'product_sku' => $item['product']->sku,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
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
