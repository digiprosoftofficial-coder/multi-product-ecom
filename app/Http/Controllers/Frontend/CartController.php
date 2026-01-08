<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return view('frontend.cart.index', ['cartItems' => [], 'total' => 0]);
        }

        // Eager load all products at once to avoid N+1 queries
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)
            ->with('category')
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $product->final_price * $item['quantity'];
                $total += $item['subtotal'];
                $cartItems[] = $item;
            }
        }

        return view('frontend.cart.index', compact('cartItems', 'total'));
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
            return back()->with('error', 'Insufficient stock available.');
        }

        session(['cart' => $cart]);

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

    public function remove(Product $product)
    {
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        session(['cart' => []]);
        return back()->with('success', 'Cart cleared successfully.');
    }
}

