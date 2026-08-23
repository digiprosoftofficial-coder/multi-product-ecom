@extends('admin.layouts.master')

@section('title', 'Shop page')
@section('page-title', 'Shop page')

@section('content')
<form action="{{ route('admin.shop-page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.pages.partials.banner-fields', ['pageKey' => 'shop', 'label' => 'Shop', 'pages' => $pages])

    <div class="d-flex gap-2 sticky-bottom bg-body py-3 border-top">
        <button type="submit" class="btn btn-primary">Save Shop page</button>
    </div>
</form>
@endsection

@include('admin.pages.partials.banner-assets')
