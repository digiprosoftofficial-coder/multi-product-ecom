@extends('admin.layouts.master')

@section('title', 'Checkout page')
@section('page-title', 'Checkout page')

@section('content')
<form action="{{ route('admin.checkout-page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'checkout', 'label' => 'Checkout', 'pages' => $pages])

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save Checkout page</button>
    </div>
</form>
@endsection

@include('admin.pages.partials.banner-assets')
