@extends('admin.layouts.master')

@section('title', 'Message')
@section('page-title', 'Message detail')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to messages
    </a>
    <div class="d-flex flex-wrap gap-2">
        @if($message->is_read)
            <form action="{{ route('admin.contact-messages.unread', $message) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-secondary btn-sm">Mark unread</button>
            </form>
        @else
            <form action="{{ route('admin.contact-messages.read', $message) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-success btn-sm">Mark read</button>
            </form>
        @endif
        <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Your message')) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-reply me-1"></i> Reply by email
        </a>
        <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST"
              onsubmit="return confirm('Delete this message?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="text-muted small mb-1">From</div>
                <div class="fw-semibold">{{ $message->name }}</div>
                <div><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></div>
                @if($message->phone)
                    <div><a href="tel:{{ preg_replace('/\s+/', '', $message->phone) }}">{{ $message->phone }}</a></div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Received</div>
                <div>{{ $message->created_at?->format('d M Y, h:i A') }}</div>
                <div class="mt-2">
                    @if($message->is_read)
                        <span class="badge bg-secondary">Read</span>
                    @else
                        <span class="badge bg-success">Unread</span>
                    @endif
                </div>
            </div>
            <div class="col-12">
                <div class="text-muted small mb-1">Subject</div>
                <div class="fw-semibold">{{ $message->subject ?: 'No subject' }}</div>
            </div>
        </div>

        <hr>

        <div class="text-muted small mb-2">Message</div>
        <div class="p-3 rounded-3 bg-light" style="white-space: pre-wrap;">{{ $message->message }}</div>
    </div>
</div>
@endsection
