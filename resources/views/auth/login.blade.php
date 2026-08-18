@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    main { background: #f6f7f2; }
    .login-card { background: #fff; }
    .login-card .form-control {
        background: #fff;
        border: 1px solid #cfd5cc;
        box-shadow: none;
    }
    .login-card .form-control:focus {
        border-color: #6BB252;
        box-shadow: 0 0 0 0.2rem rgba(107, 178, 82, 0.2);
    }
    .login-card .form-check-input {
        border: 1px solid #cfd5cc;
        background-color: #fff;
    }
    .login-card .form-check-input:checked {
        background-color: #6BB252;
        border-color: #6BB252;
    }
</style>
@endpush

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-5 col-lg-4">
            <div class="card login-card border-0 shadow rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h4 text-center mb-1">Login</h1>
                    <p class="text-muted text-center small mb-4">Sign in to your {{ site_name() }} account</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
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
                            <button type="submit" class="btn btn-primary btn-lg rounded-1">Login</button>
                        </div>

                        <div class="text-center mt-4 mb-0">
                            <p class="mb-0">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
