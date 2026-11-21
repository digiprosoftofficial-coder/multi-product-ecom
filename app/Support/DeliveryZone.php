<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryZone
{
    /**
     * Zone rates for a cart. Shipping is 0 only when every product is free delivery.
     * A mix of free and charged products keeps the store zone rate, charged once.
     *
     * @param  iterable<int, object>  $products
     * @return array{inside: int, outside: int}
     */
    public static function ratesFor(iterable $products): array
    {
        $count = 0;
        $allFree = true;

        foreach ($products as $product) {
            $count++;
            if (! ($product->free_delivery ?? false)) {
                $allFree = false;
            }
        }

        if ($count > 0 && $allFree) {
            return ['inside' => 0, 'outside' => 0];
        }

        return [
            'inside' => taka(Setting::get('shipping_inside_dhaka', 60)),
            'outside' => taka(Setting::get('shipping_outside_dhaka', 120)),
        ];
    }

    /**
     * Suggest inside_dhaka or outside_dhaka from the visitor IP.
     * Falls back to inside_dhaka when the address is private or the lookup fails.
     */
    public static function suggest(?string $ip): string
    {
        if (! self::isPublicIp($ip)) {
            return 'inside_dhaka';
        }

        $cacheKey = 'delivery-zone:'.$ip;
        $cached = Cache::get($cacheKey);
        if ($cached === 'inside_dhaka' || $cached === 'outside_dhaka') {
            return $cached;
        }

        $zone = self::lookup($ip);
        if ($zone === null) {
            return 'inside_dhaka';
        }

        Cache::put($cacheKey, $zone, now()->addHours(12));

        return $zone;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromLookup(array $payload): ?string
    {
        if (($payload['status'] ?? '') !== 'success') {
            return null;
        }

        $country = strtoupper((string) ($payload['countryCode'] ?? ''));
        $place = strtolower(trim(((string) ($payload['city'] ?? '')).' '.((string) ($payload['regionName'] ?? ''))));

        if ($country !== '' && $country !== 'BD') {
            return 'outside_dhaka';
        }

        if ($place !== '' && str_contains($place, 'dhaka')) {
            return 'inside_dhaka';
        }

        if ($country === 'BD') {
            return 'outside_dhaka';
        }

        return null;
    }

    private static function lookup(string $ip): ?string
    {
        try {
            $response = Http::timeout(2)
                ->connectTimeout(1)
                ->acceptJson()
                ->get('http://ip-api.com/json/'.$ip, [
                    'fields' => 'status,countryCode,city,regionName',
                ]);

            if (! $response->ok()) {
                return null;
            }

            $json = $response->json();

            return is_array($json) ? self::fromLookup($json) : null;
        } catch (\Throwable $e) {
            Log::debug('Delivery zone lookup failed', ['message' => $e->getMessage()]);

            return null;
        }
    }

    private static function isPublicIp(?string $ip): bool
    {
        if (! is_string($ip) || $ip === '') {
            return false;
        }

        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;
    }
}
