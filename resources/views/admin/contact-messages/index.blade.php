@extends('admin.layouts.master')

@section('title', 'Messages')
@section('page-title', 'Contact messages')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <p class="text-muted mb-0 small">
        Messages from the website contact form.
        @if($unreadCount > 0)
            <span class="badge bg-success" id="messageUnreadBadge">{{ $unreadCount }} unread</span>
        @endif
    </p>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="messageFilterForm" method="GET" action="{{ route('admin.contact-messages.index') }}" class="row g-3">
            <div class="col-md-7">
                <input type="text" name="search" id="messageSearch" class="form-control"
                       placeholder="Search name, email, phone, subject..."
                       value="{{ request('search') }}" autocomplete="off">
            </div>
            <div class="col-md-3">
                <select name="status" id="messageStatus" class="form-select">
                    <option value="">All messages</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-secondary w-100" id="messageFilterClear" title="Clear filters">
                    <i class="fas fa-times me-1"></i> Clear
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0" id="messageResults">
        @include('admin.contact-messages.partials.results')
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('messageFilterForm');
    var searchInput = document.getElementById('messageSearch');
    var statusSelect = document.getElementById('messageStatus');
    var clearBtn = document.getElementById('messageFilterClear');
    var results = document.getElementById('messageResults');
    var indexUrl = @json(route('admin.contact-messages.index'));
    var debounceTimer = null;
    var abortController = null;

    function currentParams(page) {
        var params = new URLSearchParams();
        var search = searchInput.value.trim();
        if (search) params.set('search', search);
        if (statusSelect.value) params.set('status', statusSelect.value);
        if (page) params.set('page', page);
        return params;
    }

    function loadMessages(page) {
        var params = currentParams(page);
        var url = indexUrl + (params.toString() ? '?' + params.toString() : '');

        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();
        results.style.opacity = '0.55';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            signal: abortController.signal
        }).then(function (response) {
            if (!response.ok) throw new Error('Failed to load messages');
            return response.text();
        }).then(function (html) {
            results.innerHTML = html;
            results.style.opacity = '1';
            history.replaceState(null, '', url);
        }).catch(function (error) {
            if (error.name === 'AbortError') return;
            results.style.opacity = '1';
        });
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        loadMessages();
    });

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            loadMessages();
        }, 300);
    });

    statusSelect.addEventListener('change', function () {
        loadMessages();
    });

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        statusSelect.value = '';
        loadMessages();
    });

    results.addEventListener('click', function (event) {
        var link = event.target.closest('#messagePagination a');
        if (!link) return;
        event.preventDefault();
        var href = link.getAttribute('href');
        if (!href || href === '#') return;
        var page = new URL(href, window.location.origin).searchParams.get('page');
        loadMessages(page);
    });
});
</script>
@endpush
