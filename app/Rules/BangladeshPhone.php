<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BangladeshPhone implements ValidationRule
{
    /**
     * Accepts local (01XXXXXXXXX) or country-code (8801XXXXXXXXX / +8801XXXXXXXXX) formats.
     * Spaces, dashes, and other non-digit characters are ignored when checking.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value) && ! is_numeric($value)) {
            $fail('The :attribute must be a valid Bangladesh mobile number.');

            return;
        }

        if (! self::isValid((string) $value)) {
            $fail('The :attribute must be a valid Bangladesh mobile number (e.g. 01XXXXXXXXX).');
        }
    }

    public static function isValid(string $value): bool
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        return (bool) preg_match('/^(?:88)?01[3-9]\d{8}$/', $digits);
    }

    /**
     * Normalize to local 01XXXXXXXXX when the value is a valid BD mobile.
     */
    public static function normalize(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value) ?? '';

        if (preg_match('/^880(1[3-9]\d{8})$/', $digits, $matches)) {
            return '0'.$matches[1];
        }

        if (preg_match('/^01[3-9]\d{8}$/', $digits)) {
            return $digits;
        }

        return null;
    }

    /**
     * Digits with country code for wa.me links (8801XXXXXXXXX).
     */
    public static function toWhatsAppDigits(?string $value): ?string
    {
        $local = self::normalize($value);

        if ($local === null) {
            return null;
        }

        return '880'.substr($local, 1);
    }
}
