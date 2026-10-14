@php
    $authColor = \App\Support\Homepage::normalizeColor(setting('auth_page_color', '#8d6b78'), '#8d6b78');
    $authImage = setting_image_url(setting('auth_page_image'));
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

        return sprintf(
            '#%02x%02x%02x',
            (int) round($r1 + ($r2 - $r1) * $amount),
            (int) round($g1 + ($g2 - $g1) * $amount),
            (int) round($b1 + ($b2 - $b1) * $amount)
        );
    };
    $authBackdrop = $mix($authColor, '#e6d2cc', 0.62);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Account') – {{ site_name() }}</title>
    @include('partials.favicon')
    @hasSection('seo')
        @yield('seo')
    @else
        @include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    @include('auth.partials.layout-styles')
</head>
<body class="auth-screen" style="--auth-color: {{ $authColor }}; --auth-backdrop: {{ $authBackdrop }};">
    <main class="auth-stage">
        <div class="auth-frame">
            <section class="auth-panel">
                @yield('content')
            </section>
            <aside class="auth-visual" aria-hidden="true">
                @if($authImage)
                    <img src="{{ $authImage }}" alt="">
                @else
                    <div class="auth-visual-fallback"></div>
                @endif
            </aside>
        </div>
    </main>
</body>
</html>
