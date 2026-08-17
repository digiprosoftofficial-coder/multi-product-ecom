@extends('layouts.app')

@section('title', 'About – '.site_name())

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-4">{{ $title ?? 'About '.site_name() }}</h1>
            @if(filled($content))
                <div class="page-content">
                    {!! $content !!}
                </div>
            @else
                <p class="text-muted">About content will appear here after it is added in Admin → Pages.</p>
            @endif
        </div>
    </div>
</div>
@endsection
