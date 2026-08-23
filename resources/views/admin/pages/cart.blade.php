@extends('admin.layouts.master')

@section('title', 'Cart page')
@section('page-title', 'Cart page')

@section('content')
<form action="{{ route('admin.cart-page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'cart', 'label' => 'Cart', 'pages' => $pages])

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save Cart page</button>
    </div>
</form>
@endsection

@include('admin.pages.partials.banner-assets')
