<?php

namespace App\Support;

use App\Models\Setting;

class PaymentMethod
{
    /**
     * @return array<string, array{label: string, icon: string, hint: string, color: string, enabled_key: string}>
     */
    public static function all(): array
    {
        return [
            'cash_on_delivery' => [
                'label' => 'Cash on Delivery',
                'icon' => 'fa-money-bill-wave',
                'hint' => 'Pay when you receive your order',
                'color' => '#6BB252',
                'enabled_key' => 'payment_cod_enabled',
            ],
            'bkash' => [
                'label' => 'bKash',
                'icon' => 'fa-mobile-screen-button',
                'hint' => 'Send payment to our bKash number',
                'color' => '#e2136e',
                'enabled_key' => 'payment_bkash_enabled',
            ],
            'nagad' => [
                'label' => 'Nagad',
                'icon' => 'fa-mobile-screen-button',
                'hint' => 'Send payment to our Nagad number',
                'color' => '#f6921e',
                'enabled_key' => 'payment_nagad_enabled',
            ],
            'rocket' => [
                'label' => 'Rocket',
                'icon' => 'fa-mobile-screen-button',
                'hint' => 'Send payment to our Rocket number',
                'color' => '#8b2d88',
                'enabled_key' => 'payment_rocket_enabled',
            ],
        ];
    }

    public static function isEnabled(string $method): bool
    {
        $all = self::all();
        if (! isset($all[$method])) {
            return false;
        }

        return Setting::get($all[$method]['enabled_key'], '1') === '1';
    }

    /**
     * Enabled methods only (for checkout).
     *
     * @return array<string, array{label: string, icon: string, hint: string, color: string}>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::all() as $key => $method) {
            if (! self::isEnabled($key)) {
                continue;
            }

            $options[$key] = [
                'label' => $method['label'],
                'icon' => $method['icon'],
                'hint' => $method['hint'],
                'color' => $method['color'],
            ];
        }

        return $options;
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_keys(self::options());
    }

    /** @return list<string> */
    public static function allValues(): array
    {
        return array_keys(self::all());
    }

    public static function defaultMethod(): ?string
    {
        $values = self::values();

        return $values[0] ?? null;
    }

    public static function label(string $method): string
    {
        return self::all()[$method]['label'] ?? ucwords(str_replace('_', ' ', $method));
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
