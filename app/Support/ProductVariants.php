<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductVariants
{
    public static function parseList(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return [];
        }

        $parts = preg_split('/\s*,\s*/', $raw) ?: [];
        $values = [];
        foreach ($parts as $part) {
            $value = trim($part);
            if ($value === '' || in_array($value, $values, true)) {
                continue;
            }
            $values[] = Str::limit($value, 40, '');
        }

        return array_slice($values, 0, 12);
    }

    public static function combinations(array $option1, array $option2): array
    {
        $rows = [];
        $second = $option2 === [] ? [''] : $option2;

        foreach ($option1 as $one) {
            foreach ($second as $two) {
                $rows[] = ['option1' => $one, 'option2' => $two];
            }
        }

        return array_slice($rows, 0, 48);
    }

    public static function skuFor(Product $product, string $option1, string $option2): string
    {
        $parts = array_filter([
            $product->sku ?: 'SKU',
            $option1,
            $option2,
        ], fn ($part) => $part !== '');

        $sku = strtoupper(Str::slug(implode('-', $parts), '-'));

        $base = $sku !== '' ? $sku : 'VAR-'.strtoupper(Str::random(6));
        $candidate = $base;
        $i = 2;
        while (ProductVariant::where('sku', $candidate)->where('product_id', '!=', $product->id)->exists()
            || ProductVariant::where('sku', $candidate)->where('product_id', $product->id)->exists()) {
            if (! ProductVariant::where('sku', $candidate)->where('product_id', $product->id)
                ->where('option1', $option1)->where('option2', $option2)->exists()) {
                $candidate = $base.'-'.$i;
                $i++;
                continue;
            }
            break;
        }

        return $candidate;
    }

    public static function sync(Product $product, bool $enabled, array $payload): void
    {
        if (! $enabled) {
            $product->variants()->delete();
            $product->forceFill([
                'has_variants' => false,
                'option1_name' => null,
                'option2_name' => null,
            ])->save();

            return;
        }

        $option1Name = trim((string) ($payload['option1_name'] ?? 'Size')) ?: 'Size';
        $option2Name = trim((string) ($payload['option2_name'] ?? 'Color')) ?: 'Color';
        $option1 = self::parseList($payload['option1_values'] ?? '');
        $option2 = self::parseList($payload['option2_values'] ?? '');

        if ($option1 === []) {
            $option1 = ['Default'];
        }

        $wanted = [];
        $stocks = collect($payload['variants'] ?? []);
        $stockLookup = [];
        foreach ($stocks as $row) {
            $key = self::comboKey((string) ($row['option1'] ?? ''), (string) ($row['option2'] ?? ''));
            $stockLookup[$key] = max(0, (int) ($row['stock'] ?? 0));
        }

        foreach (self::combinations($option1, $option2) as $combo) {
            $key = self::comboKey($combo['option1'], $combo['option2']);
            $wanted[$key] = [
                'option1' => $combo['option1'],
                'option2' => $combo['option2'],
                'stock' => $stockLookup[$key] ?? 0,
            ];
        }

        $keepIds = [];
        foreach ($wanted as $combo) {
            $existing = $product->variants()
                ->where('option1', $combo['option1'])
                ->where('option2', $combo['option2'])
                ->first();

            $variant = $product->variants()->updateOrCreate(
                [
                    'option1' => $combo['option1'],
                    'option2' => $combo['option2'],
                ],
                [
                    'sku' => $existing?->sku ?: self::uniqueSku($product, $combo['option1'], $combo['option2']),
                    'stock' => $combo['stock'],
                ]
            );
            $keepIds[] = $variant->id;
        }

        $product->variants()->whereNotIn('id', $keepIds ?: [0])->delete();

        $total = (int) $product->variants()->sum('stock');
        $product->forceFill([
            'has_variants' => true,
            'option1_name' => $option1Name,
            'option2_name' => $option2 === [] ? null : $option2Name,
            'stock' => $total,
        ])->save();
    }

    public static function comboKey(string $option1, string $option2): string
    {
        return mb_strtolower($option1).'|'.mb_strtolower($option2);
    }

    protected static function uniqueSku(Product $product, string $option1, string $option2): string
    {
        $base = strtoupper(Str::slug(implode('-', array_filter([$product->sku ?: 'SKU', $option1, $option2])), '-')) ?: 'VAR';
        $sku = $base;
        $i = 2;
        while (
            ProductVariant::where('sku', $sku)->exists()
            || Product::where('sku', $sku)->exists()
        ) {
            $sku = $base.'-'.$i;
            $i++;
        }

        return $sku;
    }
}
