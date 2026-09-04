<?php

namespace App\Support;

use App\Models\Order;
use App\Models\Product;

class Tracking
{
    public static function googleAnalyticsId(): ?string
    {
        $id = strtoupper(trim((string) setting('google_analytics_id', '')));

        return preg_match('/^G-[A-Z0-9]+$/', $id) ? $id : null;
    }

    public static function googleTagManagerId(): ?string
    {
        $id = strtoupper(trim((string) setting('google_tag_manager_id', '')));

        return preg_match('/^GTM-[A-Z0-9]+$/', $id) ? $id : null;
    }

    public static function facebookPixelId(): ?string
    {
        $id = preg_replace('/\D+/', '', (string) setting('facebook_pixel_id', ''));

        return $id !== '' ? $id : null;
    }

    public static function currency(): string
    {
        return Seo::currencyCode();
    }

    public static function productPayload(Product $product, int $quantity = 1): array
    {
        $qty = max(1, $quantity);
        $price = round((float) $product->final_price, 2);

        return [
            'id' => (string) $product->id,
            'sku' => (string) $product->sku,
            'name' => $product->name,
            'price' => $price,
            'quantity' => $qty,
            'value' => round($price * $qty, 2),
            'currency' => self::currency(),
        ];
    }

    /**
     * @param  array<int, array{product: Product, quantity: int}>  $cartItems
     */
    public static function cartPayload(array $cartItems, float $value): array
    {
        $items = [];

        foreach ($cartItems as $row) {
            $product = $row['product'] ?? null;
            if (! $product instanceof Product) {
                continue;
            }

            $items[] = self::productPayload($product, (int) ($row['quantity'] ?? 1));
        }

        return [
            'currency' => self::currency(),
            'value' => round($value, 2),
            'items' => $items,
        ];
    }

    public static function orderPayload(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id' => (string) ($item->product_id ?: $item->product_sku),
                'sku' => (string) $item->product_sku,
                'name' => $item->product_name,
                'price' => round((float) $item->price, 2),
                'quantity' => (int) $item->quantity,
                'value' => round((float) $item->total, 2),
                'currency' => self::currency(),
            ];
        }

        return [
            'transaction_id' => (string) $order->order_number,
            'currency' => self::currency(),
            'value' => round((float) $order->total, 2),
            'tax' => round((float) $order->tax + (float) $order->vat, 2),
            'items' => $items,
        ];
    }
}
