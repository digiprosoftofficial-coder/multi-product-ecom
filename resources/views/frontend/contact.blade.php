@extends('layouts.app')

@section('title', 'Contact – '.site_name())

@section('seo')
@include('frontend.partials.seo-meta', [
    'title' => 'Contact',
    'description' => \App\Support\Seo::excerpt(setting('contact_intro'), 'Contact '.site_name().'. Reach us by phone, email, or send a message.'),
    'url' => route('contact'),
    'jsonLd' => \App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Contact', 'url' => route('contact')],
    ]),
])
@endsection

@push('styles')
<style>
    .contact-page {
        background: #fff;
    }
    .contact-info-card {
        height: 100%;
        border: 1px solid #eef1ea;
        border-radius: 1rem;
        padding: 1.25rem 1.25rem 1.1rem;
        background: #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .contact-info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(107, 178, 82, 0.12);
    }
    .contact-info-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(107, 178, 82, 0.12);
        color: #6BB252;
        font-size: 1.05rem;
        margin-bottom: .85rem;
    }
    .contact-info-card h2 {
        font-size: .95rem;
        margin-bottom: .35rem;
    }
    .contact-info-card p,
    .contact-info-card a {
        color: #475569;
        margin-bottom: 0;
        font-size: .95rem;
        text-decoration: none;
    }
    .contact-info-card a:hover {
        color: #6BB252;
    }
    .contact-panel {
        border: 1px solid #eef1ea;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        height: 100%;
    }
    .contact-panel-header {
        padding: 1.25rem 1.5rem .25rem;
    }
    .contact-panel-header h2 {
        font-size: 1.15rem;
        margin-bottom: .35rem;
    }
    .contact-panel-header p {
        color: #64748b;
        font-size: .92rem;
        margin-bottom: 0;
    }
    .contact-panel-body {
        padding: 0 1.5rem 1.5rem;
    }
    .contact-form .form-control,
    .contact-form .form-select {
        border-color: #d8dfd3;
        min-height: 46px;
    }
    .contact-form .form-control:focus {
        border-color: #6BB252;
        box-shadow: 0 0 0 0.2rem rgba(107, 178, 82, 0.18);
    }
    .contact-form textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }
    .contact-map-wrap {
        min-height: 360px;
        background: #f3f6f1;
    }
    .contact-map-wrap iframe {
        display: block;
        width: 100%;
        min-height: 360px;
        border: 0;
    }
    .contact-map-placeholder {
        min-height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #64748b;
        padding: 2rem;
        background: linear-gradient(180deg, #f8faf9 0%, #eef3ea 100%);
    }
</style>
@endpush

@section('content')
@php
    $phone = setting('contact_phone');
    $email = setting('contact_email');
    $address = setting('contact_address');
    $hours = setting('contact_hours');
    $hasInfo = $phone || $email || $address || $hours;
@endphp

@include('frontend.components.page-banner', [
    'page' => 'contact',
    'fallbackTitle' => 'Contact Us',
])

<section class="contact-page">
    <div class="container-lg py-5">
        @if($hasInfo)
            <div class="row g-4 mb-4 mb-lg-5">
                @if($phone)
                    <div class="col-sm-6 col-xl-3">
                        <div class="contact-info-card">
                            <div class="contact-info-icon"><i class="fa-solid fa-phone"></i></div>
                            <h2>Phone</h2>
                            <p><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></p>
                        </div>
                    </div>
                @endif
                @if($email)
                    <div class="col-sm-6 col-xl-3">
                        <div class="contact-info-card">
                            <div class="contact-info-icon"><i class="fa-solid fa-envelope"></i></div>
                            <h2>Email</h2>
                            <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
                        </div>
                    </div>
                @endif
                @if($address)
                    <div class="col-sm-6 col-xl-3">
                        <div class="contact-info-card">
                            <div class="contact-info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <h2>Address</h2>
                            <p>{!! nl2br(e($address)) !!}</p>
                        </div>
                    </div>
                @endif
                @if($hours)
                    <div class="col-sm-6 col-xl-3">
                        <div class="contact-info-card">
                            <div class="contact-info-icon"><i class="fa-solid fa-clock"></i></div>
                            <h2>Business hours</h2>
                            <p>{{ $hours }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="contact-panel">
                    <div class="contact-panel-header">
                        <h2>Send us a message</h2>
                        <p>Fill out the form and we will respond within 1–2 business days.</p>
                    </div>
                    <div class="contact-panel-body">
                        <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="contact_name" class="form-label">Full name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="contact_name" name="name"
                                           value="{{ old('name', auth()->user()?->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="contact_email" name="email"
                                           value="{{ old('email', auth()->user()?->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_phone" class="form-label">Phone <span class="text-muted">(optional)</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="contact_phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_subject" class="form-label">Subject <span class="text-muted">(optional)</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                           id="contact_subject" name="subject" value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="contact_message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror"
                                              id="contact_message" name="message" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary rounded-1 px-4">
                                        <i class="fa-solid fa-paper-plane me-2"></i>Send message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-panel h-100 d-flex flex-column">
                    <div class="contact-panel-header">
                        <h2>Find us on the map</h2>
                        <p>{{ $address ? 'Visit our store or get directions below.' : 'Add your address in Site settings to show a map here.' }}</p>
                    </div>
                    <div class="contact-map-wrap flex-grow-1">
                        @if($mapUrl)
                            <iframe
                                src="{{ $mapUrl }}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen
                                title="Store location map"
                            ></iframe>
                        @else
                            <div class="contact-map-placeholder">
                                <div>
                                    <i class="fa-solid fa-map-location-dot fa-2x mb-3 text-success"></i>
                                    <p class="mb-0">Map will appear when an address or Google Maps embed URL is added in admin settings.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @unless($hasInfo)
            <div class="alert alert-light border mt-4 mb-0">
                Contact details can be managed in <strong>Admin → Settings → Contact information</strong>.
            </div>
        @endunless
    </div>
</section>
@endsection
