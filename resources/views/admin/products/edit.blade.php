@extends('admin.layouts.master')

@section('title', 'Edit Product')
@section('page-title', 'Edit product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-arrow-left me-1"></i> Products
        </a>
        <h5 class="mb-0 mt-1">Edit {{ $product->name }}</h5>
    </div>
</div>

<form id="productForm" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.products.partials.form-body')
</form>

@if($product->images->count() > 0)
    @foreach($product->images as $image)
        <form id="delete-image-form-{{ $image->id }}"
              action="{{ route('admin.products.images.destroy', $image) }}"
              method="POST"
              class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif

@include('admin.products.partials.form-assets')
@endsection
