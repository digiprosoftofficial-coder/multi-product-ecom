@php
    $adminThemeColor = \App\Support\Homepage::normalizeColor(
        \App\Models\Setting::query()->where('key', 'admin_theme_color')->value('value'),
        ''
    );

    $mix = function (string $hex, string $toward, float $amount): string {
        $read = function (string $value): array {
            $value = ltrim(\App\Support\Homepage::normalizeColor($value, '#000000'), '#');
            if (strlen($value) === 3) {
                $value = $value[0].$value[0].$value[1].$value[1].$value[2].$value[2];
            }

            return [hexdec(substr($value, 0, 2)), hexdec(substr($value, 2, 2)), hexdec(substr($value, 4, 2))];
        };

        [$r1, $g1, $b1] = $read($hex);
        [$r2, $g2, $b2] = $read($toward);
        $amount = max(0, min(1, $amount));
        $r = (int) round($r1 + ($r2 - $r1) * $amount);
        $g = (int) round($g1 + ($g2 - $g1) * $amount);
        $b = (int) round($b1 + ($b2 - $b1) * $amount);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    };
@endphp
@if($adminThemeColor !== '')
<style>
    body.admin-panel {
        --admin-green: {{ $adminThemeColor }};
        --admin-green-dark: {{ $mix($adminThemeColor, '#000000', 0.18) }};
        --admin-green-darker: {{ $mix($adminThemeColor, '#000000', 0.45) }};
        --admin-green-light: {{ $mix($adminThemeColor, '#ffffff', 0.82) }};
        --admin-green-soft: {{ $mix($adminThemeColor, '#ffffff', 0.94) }};
        --admin-green-border: {{ $mix($adminThemeColor, '#ffffff', 0.7) }};
        --bs-primary: {{ $adminThemeColor }};
        --bs-primary-rgb: {{ implode(', ', sscanf($adminThemeColor, '#%02x%02x%02x')) }};
        --bs-link-color: var(--admin-green-dark);
        --bs-link-hover-color: var(--admin-green-darker);
    }
</style>
@endif
