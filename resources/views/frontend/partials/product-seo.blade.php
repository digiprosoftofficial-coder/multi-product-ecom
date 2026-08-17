@php
    $seoDescription = $product->seoDescription();
    $seoUrl = route('products.show', $product);
    $seoImage = $product->seoImageUrl();
    $seoTitle = $product->seoTitle();
@endphp
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ $seoUrl }}">
<meta property="og:type" content="product">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoUrl }}">
@if($seoImage)
<meta property="og:image" content="{{ $seoImage }}">
@endif
<meta property="product:price:amount" content="{{ number_format((float) $product->final_price, 2, '.', '') }}">
<meta property="product:price:currency" content="USD">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if($seoImage)
<meta name="twitter:image" content="{{ $seoImage }}">
@endif
<script type="application/ld+json">{!! json_encode($product->jsonLd(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
