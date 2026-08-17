@extends('layouts.app')

@section('title', 'Contact – '.site_name())

@section('content')
<div class="container-lg py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-4">Contact</h1>
            @if(setting('contact_intro'))
                <p class="text-muted mb-4">{{ setting('contact_intro') }}</p>
            @endif

            <div class="card">
                <div class="card-body">
                    @if(setting('contact_phone') || setting('contact_email') || setting('contact_address') || setting('contact_hours'))
                        @if(setting('contact_phone'))
                            <p class="mb-2"><strong>Phone:</strong> {{ setting('contact_phone') }}</p>
                        @endif
                        @if(setting('contact_email'))
                            <p class="mb-2"><strong>Email:</strong> <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a></p>
                        @endif
                        @if(setting('contact_address'))
                            <p class="mb-2"><strong>Address:</strong><br>{!! nl2br(e(setting('contact_address'))) !!}</p>
                        @endif
                        @if(setting('contact_hours'))
                            <p class="mb-0"><strong>Hours:</strong> {{ setting('contact_hours') }}</p>
                        @endif
                    @else
                        <p class="text-muted mb-0">Contact details will appear here after they are added in Site settings.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
