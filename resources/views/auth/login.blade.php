@extends('layouts.app')

@section('title', 'Login')

@push('styles')
@include('auth.partials.layout-styles')
@endpush

@section('content')
<div class="auth-shell">
    <div class="card login-card border-0 shadow rounded-4">
        <div class="card-body">
            <h1 class="h4 text-center mb-1">Login</h1>
            <p class="text-muted text-center small mb-4">Sign in to your {{ site_name() }} account</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
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

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-1">Login</button>
                </div>

                <div class="text-center mt-4 mb-0">
                    <p class="mb-0 small">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
