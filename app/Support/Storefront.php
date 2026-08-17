<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class Storefront
{
    public static function cartData(): array
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return ['cartItems' => [], 'cartTotal' => 0];
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->with('category')
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $cartTotal = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);
            if (! $product) {
                continue;
            }

            $item['product'] = $product;
            $item['subtotal'] = $product->final_price * $item['quantity'];
            $cartTotal += $item['subtotal'];
            $cartItems[] = $item;
        }

        return ['cartItems' => $cartItems, 'cartTotal' => $cartTotal];
    }

    public static function navCategories(): Collection
    {
        return self::shopCategories();
    }

    public static function shopCategories(): Collection
    {
        $children = function ($query) {
            $query->where('status', 1)
                ->orderBy('name')
                ->with(['children' => function ($childQuery) {
                    $childQuery->where('status', 1)->orderBy('name');
                }]);
        };

        return Category::where('status', 1)
            ->whereNull('parent_id')
            ->with(['children' => $children])
            ->orderBy('name')
            ->get();
    }
}
