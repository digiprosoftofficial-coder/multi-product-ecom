@extends('layouts.app')

@section('title', 'Privacy policy – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => 'Privacy Policy',
    'description' => \App\Support\Seo::excerpt($content ?? '', 'Privacy policy for '.site_name().'.'),
    'url' => route('privacy'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Privacy Policy', 'url' => route('privacy')],
    ]),
])
@endsection

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'privacy',
    'fallbackTitle' => 'Privacy Policy',
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
