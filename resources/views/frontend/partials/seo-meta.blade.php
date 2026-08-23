@php
    use App\Support\Seo;

    $seoTitle = $title ?? site_name();
    $seoDescription = $description ?? Seo::defaultDescription();
    $seoUrl = $url ?? url()->current();
    $seoType = $type ?? 'website';
    $seoImage = $image ?? Seo::ogImageUrl();
    $seoCurrency = $currency ?? Seo::currencyCode();
    $seoRobots = $robots ?? null;
    $schemas = [];

    if (! empty($jsonLd)) {
        if (isset($jsonLd['@context']) || isset($jsonLd['@type'])) {
            $schemas = [$jsonLd];
        } else {
            $schemas = $jsonLd;
        }
    }
@endphp
<meta name="description" content="{{ $seoDescription }}">
@if($seoRobots)
<meta name="robots" content="{{ $seoRobots }}">
@endif
<link rel="canonical" href="{{ $seoUrl }}">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:site_name" content="{{ site_name() }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoUrl }}">
@if($seoImage)
<meta property="og:image" content="{{ $seoImage }}">
@endif
@if($seoType === 'product' && isset($price))
<meta property="product:price:amount" content="{{ number_format((float) $price, 2, '.', '') }}">
<meta property="product:price:currency" content="{{ $seoCurrency }}">
@endif
<meta name="twitter:card" content="{{ $seoImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if($seoImage)
<meta name="twitter:image" content="{{ $seoImage }}">
@endif
@foreach($schemas as $schema)
@if(! empty($schema))
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endforeach
