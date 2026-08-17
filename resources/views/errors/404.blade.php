@extends('layouts.app')

@section('title', 'Page not found – '.site_name())

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center py-5">
            <p class="display-6 fw-semibold mb-2">404</p>
            <h1 class="h3 mb-3">Page not found</h1>
            <p class="text-muted mb-4">The page you are looking for does not exist or may have been moved.</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('home') }}" class="btn btn-primary">Go home</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Browse products</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-secondary">Contact us</a>
            </div>
        </div>
    </div>
</div>
@endsection
