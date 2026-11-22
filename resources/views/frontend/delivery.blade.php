@extends('layouts.app')

@section('title', __('storefront.delivery_information').' – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => 'Delivery Information',
    'description' => \App\Support\Seo::excerpt($content ?? '', 'Delivery information and shipping details for '.site_name().'.'),
    'url' => route('delivery'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => __('storefront.home'), 'url' => route('home')],
        ['name' => 'Delivery Information', 'url' => route('delivery')],
    ]),
])
@endsection

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'delivery',
    'fallbackTitle' => 'Delivery Information',
])

<div class="container-lg py-5">
    @if(filled($content))
        <div class="page-content text-start">
            {!! $content !!}
        </div>
    @else
        <p class="text-muted text-start mb-0">This page has not been published yet.</p>
    @endif
</div>
@endsection
