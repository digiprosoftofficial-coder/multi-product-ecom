@extends('layouts.gadget')

@section('title', ($title ?? 'Page').' – '.site_name())

@section('content')
<h1 class="h4 mb-4">{{ $title }}</h1>
@if(!empty($content))
  <div class="text-muted">{!! $content !!}</div>
@else
  <p class="text-muted">This page has no content yet.</p>
@endif
@endsection
