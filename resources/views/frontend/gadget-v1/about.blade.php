@extends('layouts.gadget')

@section('title', ($title ?? 'About').' – '.site_name())

@section('content')
<h1 class="h4 mb-4">{{ $title ?? 'About us' }}</h1>
<div class="row">
  <div class="col-lg-8">
    @if(!empty($content))
      <div class="text-muted">{!! $content !!}</div>
    @else
      <p class="text-muted">We are a modern gadget store focused on accessories and tech for everyday life.</p>
    @endif
  </div>
</div>
@endsection
