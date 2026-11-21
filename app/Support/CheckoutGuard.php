<?php

namespace App\Support;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutGuard
{
    public static function question(): string
    {
        $left = random_int(2, 9);
        $right = random_int(1, 8);
        session(['checkout_captcha' => $left + $right]);

        return $left.' + '.$right;
    }

    public static function passesCaptcha(Request $request): bool
    {
        if (filled($request->input('company_website'))) {
            return false;
        }

        $expected = session()->pull('checkout_captcha');
        $answer = $request->input('captcha_answer');

        return $expected !== null
            && $answer !== null
            && $answer !== ''
            && (int) $answer === (int) $expected;
    }

    public static function tooManyFromPhone(string $phone): bool
    {
        return Order::query()
            ->where('customer_phone', $phone)
            ->where('created_at', '>=', now()->subHour())
            ->count() >= 3;
    }

    /**
     * @param  array<int, int>  $productIds
     */
    public static function pendingDuplicate(string $phone, array $productIds): ?Order
    {
        if ($productIds === []) {
            return null;
        }

        return Order::query()
            ->where('customer_phone', $phone)
            ->where('order_status', 'pending')
            ->whereHas('items', fn ($query) => $query->whereIn('product_id', $productIds))
            ->latest('id')
            ->first();
    }
}
