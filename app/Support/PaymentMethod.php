<?php

namespace App\Support;

use App\Models\Setting;

class PaymentMethod
{
    /**
     * @return array<string, array{label: string, icon: string, hint: string, color: string}>
     */
    public static function options(): array
    {
        return [
            'cash_on_delivery' => [
                'label' => 'Cash on Delivery',
                'icon' => 'fa-money-bill-wave',
                'hint' => 'Pay when you receive your order',
                'color' => '#6BB252',
            ],
            'bkash' => [
                'label' => 'bKash',
                'icon' => 'fa-mobile-screen-button',
                'hint' => 'Send payment to our bKash number',
                'color' => '#e2136e',
            ],
            'nagad' => [
                'label' => 'Nagad',
                'icon' => 'fa-mobile-screen-button',
                'hint' => 'Send payment to our Nagad number',
                'color' => '#f6921e',
            ],
            'rocket' => [
                'label' => 'Rocket',
                'icon' => 'fa-mobile-screen-button',
                'hint' => 'Send payment to our Rocket number',
                'color' => '#8b2d88',
            ],
        ];
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_keys(self::options());
    }

    public static function label(string $method): string
    {
        return self::options()[$method]['label'] ?? ucwords(str_replace('_', ' ', $method));
    }

    public static function isMobileWallet(string $method): bool
    {
        return in_array($method, ['bkash', 'nagad', 'rocket'], true);
    }

    public static function walletNumber(string $method): ?string
    {
        $number = match ($method) {
            'bkash' => Setting::get('payment_bkash_number', ''),
            'nagad' => Setting::get('payment_nagad_number', ''),
            'rocket' => Setting::get('payment_rocket_number', ''),
            default => '',
        };

        return trim((string) $number) !== '' ? trim((string) $number) : null;
    }
}
