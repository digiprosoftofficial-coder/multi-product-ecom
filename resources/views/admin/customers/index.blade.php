@extends('admin.layouts.master')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Total customers</div>
                <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Registered users</div>
                <div class="fs-4 fw-bold">{{ $stats['registered'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Guest checkout</div>
                <div class="fs-4 fw-bold">{{ $stats['guest'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="customerFilterForm" method="GET" action="{{ route('admin.customers.index') }}" class="row g-3">
            <div class="col-md-7">
                <input type="text" name="search" id="customerSearch" class="form-control"
                       placeholder="Search name, email, or phone..."
                       value="{{ $search }}" autocomplete="off">
            </div>
            <div class="col-md-3">
                <select name="type" id="customerType" class="form-select">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All customers</option>
                    <option value="registered" {{ $type === 'registered' ? 'selected' : '' }}>Registered only</option>
                    <option value="guest" {{ $type === 'guest' ? 'selected' : '' }}>Guest checkout only</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-secondary w-100" id="customerFilterClear" title="Clear filters">
                    <i class="fas fa-times me-1"></i> Clear
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0" id="customerResults">
        @include('admin.customers.partials.results')
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('customerFilterForm');
    var searchInput = document.getElementById('customerSearch');
    var typeSelect = document.getElementById('customerType');
    var clearBtn = document.getElementById('customerFilterClear');
    var results = document.getElementById('customerResults');
    var indexUrl = @json(route('admin.customers.index'));
    var debounceTimer = null;
    var abortController = null;

    function currentParams(page) {
        var params = new URLSearchParams();
        var search = searchInput.value.trim();
        if (search) params.set('search', search);
        if (typeSelect.value && typeSelect.value !== 'all') params.set('type', typeSelect.value);
        if (page) params.set('page', page);
        return params;
    }

    function loadCustomers(page) {
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
            if (!response.ok) throw new Error('Failed to load customers');
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
        loadCustomers();
    });

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            loadCustomers();
        }, 300);
    });

    typeSelect.addEventListener('change', function () {
        loadCustomers();
    });

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        typeSelect.value = 'all';
        loadCustomers();
    });

    results.addEventListener('click', function (event) {
        var link = event.target.closest('#customerPagination a');
        if (!link) return;
        event.preventDefault();
        var href = link.getAttribute('href');
        if (!href || href === '#') return;
        var page = new URL(href, window.location.origin).searchParams.get('page');
        loadCustomers(page);
    });
});
</script>
@endpush
