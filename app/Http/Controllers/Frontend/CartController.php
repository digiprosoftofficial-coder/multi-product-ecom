<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CartController extends Controller
{
    /**
     * Build cart items and total from session (shared by index and sidebar).
     */
    private function getCartData(): array
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return ['cartItems' => [], 'cartTotal' => 0];
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)
            ->with('category')
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $cartTotal = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $product->final_price * $item['quantity'];
                $cartTotal += $item['subtotal'];
                $cartItems[] = $item;
            }
        }

        return ['cartItems' => $cartItems, 'cartTotal' => $cartTotal];
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

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product added to cart successfully.',
                'cartCount' => $cartCount,
                'productId' => $product->id,
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
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

        $cartCount = count($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product removed from cart.',
                'cartCount' => $cartCount,
            ]);
        }

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        session(['cart' => []]);
        return back()->with('success', 'Cart cleared successfully.');
    }
}

