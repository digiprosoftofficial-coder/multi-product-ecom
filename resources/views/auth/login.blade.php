@extends('layouts.auth')

@section('title', 'Login')

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<div class="auth-form">
    <h1>Welcome back</h1>
    <p class="auth-lead">Sign in to your {{ site_name() }} account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="visually-hidden">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 auth-password">
            <label for="password" class="visually-hidden">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" placeholder="Password" required>
            <button type="button" class="auth-password-toggle" data-target="password" aria-label="Show password">
                <i class="fas fa-eye"></i>
            </button>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="auth-submit">Login</button>

        <p class="auth-switch">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </form>
</div>
@include('auth.partials.password-toggle')
@endsection
