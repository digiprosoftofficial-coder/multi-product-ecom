@extends('layouts.auth')

@section('title', 'Create an Account')

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<div class="auth-form">
    <h1>Create account</h1>
    <p class="auth-lead">Join {{ site_name() }} to start shopping</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="visually-hidden">Full Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="visually-hidden">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="visually-hidden">Phone</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone" required>
            @error('phone')
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

        <div class="mb-4 auth-password">
            <label for="password_confirmation" class="visually-hidden">Confirm Password</label>
            <input type="password" class="form-control"
                   id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
            <button type="button" class="auth-password-toggle" data-target="password_confirmation" aria-label="Show password">
                <i class="fas fa-eye"></i>
            </button>
        </div>

        <button type="submit" class="auth-submit">Create account</button>

        <p class="auth-switch">Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </form>
</div>
@include('auth.partials.password-toggle')
@endsection
