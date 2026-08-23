@extends('layouts.app')

@section('title', 'About – '.site_name())

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
