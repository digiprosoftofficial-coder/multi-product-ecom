@extends('layouts.app')

@section('title', 'Privacy policy – '.site_name())

@section('content')
<div class="container-lg py-5">
    <h1 class="h3 mb-4 text-start">Privacy policy</h1>
    @if(filled($content))
        <div class="page-content text-start">
            {!! $content !!}
        </div>
    @else
        <p class="text-muted text-start mb-0">This page has not been published yet.</p>
    @endif
</div>
@endsection
