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
        $variant = $this->resolveVariant($request, $product);
        if ($product->hasVariants() && ! $variant) {
            $variant = $product->variants()->where('stock', '>', 0)->orderBy('id')->first();
        }

        $maxStock = $variant ? $variant->stock : $product->stock;

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.max(1, $maxStock),
            'variant_id' => 'nullable|integer',
        ]);

        if ($product->hasVariants() && ! $variant) {
            return $request->expectsJson()
                ? response()->json(['message' => 'This option is out of stock.'], 422)
                : back()->with('error', 'This option is out of stock.');
        }

        if ($maxStock < 1) {
            return $request->expectsJson()
                ? response()->json(['message' => 'This option is out of stock.'], 422)
                : back()->with('error', 'This option is out of stock.');
        }

        $cart = session('cart', []);
        $key = Storefront::cartKey($product->id, $variant?->id);
        $quantity = (int) $request->quantity;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'quantity' => $quantity,
            ];
        }

        if ($cart[$key]['quantity'] > $maxStock) {
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
        $variant = $this->resolveVariant($request, $product);
        $maxStock = $variant ? $variant->stock : $product->stock;
        $key = Storefront::cartKey($product->id, $variant?->id);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.max(1, $maxStock),
        ]);

        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $request->quantity;
            session(['cart' => $cart]);
        } elseif (isset($cart[$product->id]) && ! $variant) {
            $cart[$product->id]['quantity'] = (int) $request->quantity;
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            return response()->json($this->cartJsonPayload($product->id, $variant?->id));
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function changeVariant(Request $request, Product $product)
    {
        if (! $product->hasVariants()) {
            return $request->expectsJson()
                ? response()->json($this->cartJsonPayload($product->id))
                : back();
        }

        $to = $this->resolveVariant($request, $product);
        $fromId = $request->input('from_variant_id');

        if (! $to || $to->stock < 1) {
            return $request->expectsJson()
                ? response()->json(['message' => 'This option is out of stock.'], 422)
                : back()->with('error', 'This option is out of stock.');
        }

        $cart = session('cart', []);
        $oldKey = Storefront::cartKey($product->id, $fromId ? (int) $fromId : null);
        $newKey = Storefront::cartKey($product->id, $to->id);
        $quantity = (int) ($cart[$oldKey]['quantity'] ?? $request->input('quantity', 1));
        $quantity = max(1, $quantity);

        if ($oldKey !== $newKey) {
            unset($cart[$oldKey]);
            if (isset($cart[$newKey])) {
                $quantity += (int) $cart[$newKey]['quantity'];
            }
        }

        $quantity = min($quantity, (int) $to->stock);
        $cart[$newKey] = [
            'product_id' => $product->id,
            'variant_id' => $to->id,
            'quantity' => $quantity,
        ];
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json($this->cartJsonPayload($product->id, $to->id));
        }

        return back()->with('success', 'Options updated.');
    }

    public function remove(Request $request, Product $product)
    {
        $variant = $this->resolveVariant($request, $product);
        $key = Storefront::cartKey($product->id, $variant?->id);
        $cart = session('cart', []);

        unset($cart[$key], $cart[$product->id]);
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json($this->cartJsonPayload($product->id, $variant?->id));
        }

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        session(['cart' => []]);
        return back()->with('success', 'Cart cleared successfully.');
    }

    protected function resolveVariant(Request $request, Product $product): ?\App\Models\ProductVariant
    {
        $variantId = $request->input('variant_id');
        if (! $variantId) {
            return null;
        }

        return $product->variants()->whereKey($variantId)->first();
    }

    /**
     * Shared JSON payload for cart mutations (sidebar / checkout AJAX).
     */
    protected function cartJsonPayload(?int $productId = null, ?int $variantId = null): array
    {
        $data = $this->getCartData();
        $subtotal = (float) $data['cartTotal'];
        $taxRate = (float) Setting::get('tax_rate', 0);
        $vatRate = (float) Setting::get('vat_rate', 0);
        $tax = taka(($subtotal * $taxRate) / 100);
        $vat = taka(($subtotal * $vatRate) / 100);
        $total = $subtotal + $tax + $vat;

        $lineTotal = null;
        $quantity = null;
        if ($productId) {
            foreach ($data['cartItems'] as $item) {
                $sameProduct = (int) $item['product']->id === (int) $productId;
                $sameVariant = (int) ($item['variant_id'] ?? 0) === (int) ($variantId ?? 0);
                if ($sameProduct && $sameVariant) {
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

