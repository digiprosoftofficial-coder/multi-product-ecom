<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class Storefront
{
    public static function cartKey(int $productId, ?int $variantId = null): string
    {
        return $variantId ? 'p'.$productId.'-v'.$variantId : 'p'.$productId;
    }

    public static function cartData(): array
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return ['cartItems' => [], 'cartTotal' => 0];
        }

        $normalized = [];
        $productIds = [];
        $variantIds = [];

        foreach ($cart as $key => $item) {
            $row = self::normalizeCartRow($key, $item);
            if (! $row) {
                continue;
            }
            $normalized[] = $row;
            $productIds[] = $row['product_id'];
            if ($row['variant_id']) {
                $variantIds[] = $row['variant_id'];
            }
        }

        $products = Product::whereIn('id', array_unique($productIds))
            ->with(['category', 'variants'])
            ->get()
            ->keyBy('id');

        $variants = $variantIds === []
            ? collect()
            : ProductVariant::whereIn('id', array_unique($variantIds))->get()->keyBy('id');

        $cartItems = [];
        $cartTotal = 0;

        foreach ($normalized as $row) {
            $product = $products->get($row['product_id']);
            if (! $product) {
                continue;
            }

            $variant = $row['variant_id'] ? $variants->get($row['variant_id']) : null;
            if ($row['variant_id'] && ! $variant) {
                continue;
            }

            $quantity = max(1, (int) $row['quantity']);
            $maxStock = $variant ? (int) $variant->stock : (int) $product->stock;
            $unitPrice = (float) taka($product->final_price);
            $subtotal = $unitPrice * $quantity;
            $cartTotal += $subtotal;

            $cartItems[] = [
                'product' => $product,
                'variant' => $variant,
                'variant_id' => $variant?->id,
                'variant_label' => $variant?->label($product->option1_name, $product->option2_name),
                'sku' => $variant?->sku ?: $product->sku,
                'quantity' => $quantity,
                'max_stock' => max(1, $maxStock),
                'price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        }

        return ['cartItems' => $cartItems, 'cartTotal' => $cartTotal];
    }

    protected static function normalizeCartRow(int|string $key, mixed $item): ?array
    {
        if (! is_array($item)) {
            return null;
        }

        if (isset($item['product_id'])) {
            return [
                'product_id' => (int) $item['product_id'],
                'variant_id' => isset($item['variant_id']) ? (int) $item['variant_id'] : null,
                'quantity' => (int) ($item['quantity'] ?? 1),
            ];
        }

        if (is_numeric($key)) {
            return [
                'product_id' => (int) $key,
                'variant_id' => null,
                'quantity' => (int) ($item['quantity'] ?? 1),
            ];
        }

        return null;
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
