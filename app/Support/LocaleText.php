<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Lang;

class LocaleText
{
    public static function shouldLocalize(): bool
    {
        if (app()->getLocale() !== 'bn' || app()->runningInConsole()) {
            return false;
        }

        return ! request()->is('admin', 'admin/*');
    }

    public static function pick(?string $english, ?string $bangla): string
    {
        if (self::shouldLocalize() && is_string($bangla) && trim($bangla) !== '') {
            return $bangla;
        }

        return (string) $english;
    }

    public static function setting(string $key, mixed $value, mixed $default = null): mixed
    {
        if (! self::shouldLocalize() || str_ends_with($key, '_bn') || ! is_string($value)) {
            return $value;
        }

        $bangla = Setting::get($key.'_bn', '');
        if (is_string($bangla) && trim($bangla) !== '') {
            return $bangla;
        }

        if (! Lang::has('content.'.$key)) {
            return $value;
        }

        $englishDefault = self::englishDefault($key);
        $matchesDefault = $value === ''
            || $value === (string) $default
            || ($englishDefault !== null && $value === $englishDefault);

        return $matchesDefault ? __('content.'.$key) : $value;
    }

    public static function englishDefault(string $key): ?string
    {
        $defaults = Homepage::defaults() + PageBanner::defaults();

        return array_key_exists($key, $defaults) ? (string) $defaults[$key] : null;
    }
}
