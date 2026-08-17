<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Load active categories with their active subcategories for the Organic v1 homepage
        $categories = Category::where('status', 1)
            ->with(['subCategories' => function ($query) {
                $query->where('status', 1)
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        // Best selling products (currently using latest active, in-stock products)
        $bestSellingProducts = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->with(['category', 'images', 'orderItems'])
            ->latest()
            ->take(10)
            ->get();

        // Load cart items for sidebar
        $cart = session('cart', []);
        $cartItems = [];
        $cartTotal = 0;
        
        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)
                ->with('category')
                ->get()
                ->keyBy('id');

            foreach ($cart as $productId => $item) {
                $product = $products->get($productId);
                if ($product) {
                    $item['product'] = $product;
                    $item['subtotal'] = $product->final_price * $item['quantity'];
                    $cartTotal += $item['subtotal'];
                    $cartItems[] = $item;
                }
            }
        }

        $theme = setting('active_frontend_theme', 'organic-v1');
        return view("frontend.{$theme}.index", compact('categories', 'bestSellingProducts', 'cartItems', 'cartTotal'));
    }
}

