<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\Support\Storefront;
use App\Support\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CartController extends Controller
{
    /**
     * Build cart items and total from session (shared by index and sidebar).
     */
    private function getCartData(): array
    {
        return Storefront::cartData();
    }

    public function index()
    {
        $data = $this->getCartData();
        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = View::exists("frontend.{$theme}.cart") ? "frontend.{$theme}.cart" : 'frontend.cart.index';
        return view($view, [
            'cartItems' => $data['cartItems'],
            'total' => $data['cartTotal'],
        ]);
    }

    /**
     * Return sidebar cart HTML fragment for AJAX refresh (no page reload).
     */
    public function sidebar()
    {
        $data = $this->getCartData();
        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = View::exists("frontend.{$theme}.partials.cart-sidebar-content")
            ? "frontend.{$theme}.partials.cart-sidebar-content"
            : 'frontend.partials.cart-sidebar-content';
        $html = view($view, [
            'cartItems' => $data['cartItems'],
            'cartTotal' => $data['cartTotal'],
        ])->render();

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'quantity' => $request->quantity,
            ];
        }

        // Check stock
        if ($cart[$product->id]['quantity'] > $product->stock) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Insufficient stock available.'], 422)
                : back()->with('error', 'Insufficient stock available.');
        }

        session(['cart' => $cart]);

        $cartCount = count($cart);
        $buyNow = $request->boolean('buy_now');

        if ($request->expectsJson()) {
            $payload = [
                'message' => 'Product added to cart successfully.',
                'cartCount' => $cartCount,
                'productId' => $product->id,
                'tracking' => Tracking::productPayload($product, (int) $request->quantity),
            ];

            if ($buyNow) {
                $payload['redirect'] = route('checkout.index');
            }

            return response()->json($payload);
        }

        return $buyNow
            ? redirect()->route('checkout.index')
            : back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = (int) $request->quantity;
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            return response()->json($this->cartJsonPayload($product->id));
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request, Product $product)
    {
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            return response()->json($this->cartJsonPayload($product->id));
        }

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        session(['cart' => []]);
        return back()->with('success', 'Cart cleared successfully.');
    }

    /**
     * Shared JSON payload for cart mutations (sidebar / checkout AJAX).
     */
    protected function cartJsonPayload(?int $productId = null): array
    {
        $data = $this->getCartData();
        $subtotal = (float) $data['cartTotal'];
        $taxRate = (float) Setting::get('tax_rate', 0);
        $vatRate = (float) Setting::get('vat_rate', 0);
        $tax = ($subtotal * $taxRate) / 100;
        $vat = ($subtotal * $vatRate) / 100;
        $total = $subtotal + $tax + $vat;

        $lineTotal = null;
        $quantity = null;
        if ($productId) {
            foreach ($data['cartItems'] as $item) {
                if ((int) $item['product']->id === (int) $productId) {
                    $lineTotal = (float) $item['subtotal'];
                    $quantity = (int) $item['quantity'];
                    break;
                }
            }
        }

        return [
            'message' => 'Cart updated successfully.',
            'cartCount' => count($data['cartItems']),
            'productId' => $productId,
            'quantity' => $quantity,
            'lineTotal' => $lineTotal !== null ? money($lineTotal) : null,
            'subtotal' => money($subtotal),
            'tax' => money($tax),
            'vat' => money($vat),
            'total' => money($total),
            'totalFormatted' => money($total),
            'empty' => count($data['cartItems']) === 0,
        ];
    }
}

