@extends('admin.layouts.master')

@section('title', 'Create Product')
@section('page-title', 'Add product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-arrow-left me-1"></i> Products
        </a>
        <h5 class="mb-0 mt-1">Add product</h5>
    </div>
</div>

<form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.products.partials.form-body')
</form>

@include('admin.products.partials.form-assets')
@endsection
