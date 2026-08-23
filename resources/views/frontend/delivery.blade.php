@extends('layouts.app')

@section('title', 'Delivery information – '.site_name())

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
