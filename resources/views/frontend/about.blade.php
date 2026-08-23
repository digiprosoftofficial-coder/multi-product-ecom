@extends('layouts.app')

@section('title', 'About – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => $title ?? 'About',
    'description' => \App\Support\Seo::excerpt($content ?? '', 'Learn more about '.site_name().'.'),
    'url' => route('about'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'About', 'url' => route('about')],
    ]),
])
@endsection

@section('content')
@include('frontend.components.page-banner', [
    'page' => 'about',
    'fallbackTitle' => $title ?? 'About '.site_name(),
])

<div class="container-lg py-5">
    @if(filled($content))
        <div class="page-content text-start">
            {!! $content !!}
        </div>
    @else
        <p class="text-muted mb-0 text-start">About content will appear here after it is added in Admin → Site Setting → About.</p>
    @endif
</div>
@endsection
