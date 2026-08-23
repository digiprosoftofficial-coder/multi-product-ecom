@extends('admin.layouts.master')

@section('title', 'Contact page')
@section('page-title', 'Contact page')

@section('content')
<form action="{{ route('admin.contact-page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'contact', 'label' => 'Contact', 'pages' => $pages])

    <p class="text-muted small mb-4">Phone, email, address, hours and map URL are managed in <a href="{{ route('admin.settings.index') }}">Settings → Contact information</a>.</p>

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save Contact page</button>
    </div>
</form>
@endsection

@include('admin.pages.partials.banner-assets')
