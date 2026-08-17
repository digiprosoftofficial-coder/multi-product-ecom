<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('compare_price_enabled')) {
    function compare_price_enabled(): bool
    {
        return (string) setting('enable_compare_price', '1') === '1';
    }
}
