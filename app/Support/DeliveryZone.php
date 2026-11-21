<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryZone
{
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
