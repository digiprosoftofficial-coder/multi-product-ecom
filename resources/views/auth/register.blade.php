@extends('layouts.app')

@section('title', 'Create an Account')

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@push('styles')
@include('auth.partials.layout-styles')
@endpush

@section('content')
<div class="auth-shell">
    <div class="card login-card border-0 shadow rounded-4">
        <div class="card-body">
            <h1 class="h4 text-center mb-1">Create an Account</h1>
            <p class="text-muted text-center small mb-4">Join {{ site_name() }} to start shopping</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-1">Create an Account</button>
                </div>

                <div class="text-center mt-4 mb-0">
                    <p class="mb-0 small">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
