@extends('layouts.gadget')

@section('title', 'Edit profile – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', ['robots' => 'noindex, nofollow'])
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card gadget-card border-secondary">
      <div class="card-header border-secondary"><h1 class="h5 mb-0">Edit profile</h1></div>
      <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="name" class="form-label">Full name</label>
            <input type="text" class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control bg-dark border-secondary text-white @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control bg-dark border-secondary text-white @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="01XXXXXXXXX">
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">New password (leave blank to keep current)</label>
            <input type="password" class="form-control bg-dark border-secondary text-white @error('password') is-invalid @enderror" id="password" name="password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm new password</label>
            <input type="password" class="form-control bg-dark border-secondary text-white" id="password_confirmation" name="password_confirmation">
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update profile</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
