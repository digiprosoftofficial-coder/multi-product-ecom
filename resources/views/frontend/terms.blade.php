@extends('layouts.app')

@section('title', 'Terms & conditions – '.site_name())

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-4">Terms &amp; conditions</h1>
            @if(filled($content))
                <div class="page-content">
                    {!! $content !!}
                </div>
            @else
                <p class="text-muted">This page has not been published yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
