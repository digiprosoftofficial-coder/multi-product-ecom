@extends('layouts.app')

@section('title', 'Product returns – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => 'Product Returns',
    'description' => \App\Support\Seo::excerpt($content ?? '', 'Return and refund policy for '.site_name().'.'),
    'url' => route('returns'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Product Returns', 'url' => route('returns')],
    ]),
])
@endsection

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'returns',
    'fallbackTitle' => 'Product Returns',
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
