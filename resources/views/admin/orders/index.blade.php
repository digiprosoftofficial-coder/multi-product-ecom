@extends('admin.layouts.master')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form id="orderFilterForm" method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" id="orderSearch" class="form-control"
                       placeholder="Search order, customer, email, or product..." value="{{ request('search') }}"
                       autocomplete="off">
            </div>
            <div class="col-md-4">
                <select name="status" id="orderStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-secondary w-100" id="orderFilterClear" title="Clear filters">
                    <i class="fas fa-times me-1"></i> Clear
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" id="orderResults">
        @include('admin.orders.partials.results')
    </div>
</div>
@endsection

@push('styles')
<style>
    .order-products {
        min-width: 180px;
        max-width: 280px;
    }
    .order-product-line + .order-product-line {
        margin-top: .25rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('orderFilterForm');
    var searchInput = document.getElementById('orderSearch');
    var statusSelect = document.getElementById('orderStatus');
    var clearBtn = document.getElementById('orderFilterClear');
    var results = document.getElementById('orderResults');
    var indexUrl = @json(route('admin.orders.index'));
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

    function loadOrders(page) {
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
            if (!response.ok) throw new Error('Failed to load orders');
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
        loadOrders();
    });

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            loadOrders();
        }, 300);
    });

    statusSelect.addEventListener('change', function () {
        loadOrders();
    });

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        statusSelect.value = '';
        loadOrders();
    });

    results.addEventListener('click', function (event) {
        var link = event.target.closest('#orderPagination a');
        if (!link) return;
        event.preventDefault();
        var href = link.getAttribute('href');
        if (!href || href === '#') return;
        var page = new URL(href, window.location.origin).searchParams.get('page');
        loadOrders(page);
    });
});
</script>
@endpush
