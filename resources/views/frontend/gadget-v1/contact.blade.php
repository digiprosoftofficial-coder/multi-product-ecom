@extends('layouts.gadget')

@section('title', 'Contact – '.site_name())

@section('content')
<h1 class="h4 mb-4">Contact</h1>
<div class="row g-4">
  <div class="col-md-5">
    <div class="card gadget-card border-secondary h-100">
      <div class="card-body">
        <h2 class="h6 mb-3">Get in touch</h2>
        @if(setting('contact_phone'))
          <p class="text-muted small mb-2"><i class="fas fa-phone text-accent me-2"></i>{{ setting('contact_phone') }}</p>
        @endif
        @if(setting('contact_email'))
          <p class="text-muted small mb-2"><i class="fas fa-envelope text-accent me-2"></i>{{ setting('contact_email') }}</p>
        @endif
        @if(setting('contact_address'))
          <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt text-accent me-2"></i>{{ setting('contact_address') }}</p>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card gadget-card border-secondary">
      <div class="card-body">
        <h2 class="h6 mb-3">Send a message</h2>
        <form method="POST" action="{{ route('contact.submit') }}">
          @csrf
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control bg-dark border-secondary text-white @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control bg-dark border-secondary text-white @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control bg-dark border-secondary text-white" value="{{ old('subject') }}">
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" rows="4" class="form-control bg-dark border-secondary text-white @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <button type="submit" class="btn btn-primary">Send</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
