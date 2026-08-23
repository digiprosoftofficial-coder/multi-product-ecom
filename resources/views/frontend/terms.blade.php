@extends('layouts.app')

@section('title', 'Terms & conditions – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => 'Terms & Conditions',
    'description' => \App\Support\Seo::excerpt($content ?? '', 'Terms and conditions for shopping at '.site_name().'.'),
    'url' => route('terms'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Terms & Conditions', 'url' => route('terms')],
    ]),
])
@endsection

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'terms',
    'fallbackTitle' => 'Terms & Conditions',
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
