@extends('layouts.app')

@section('title', 'Create an Account')

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
</style>
@endpush

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-5 col-lg-4">
            <div class="card login-card border-0 shadow rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h4 text-center mb-1">Create an Account</h1>
                    <p class="text-muted text-center small mb-4">Join {{ site_name() }} to start shopping</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
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

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control form-control-lg"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-1">Create an Account</button>
                        </div>

                        <div class="text-center mt-4 mb-0">
                            <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
