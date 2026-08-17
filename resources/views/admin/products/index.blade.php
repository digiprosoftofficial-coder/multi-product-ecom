@extends('admin.layouts.master')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">All Products <span class="badge bg-primary" id="productTotalBadge">{{ $products->total() }}</span></h5>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="productFilterForm" method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" id="productSearch" class="form-control"
                       placeholder="Search name or SKU..." value="{{ request('search') }}" autocomplete="off">
            </div>
            <div class="col-md-3">
                <select name="category_id" id="productCategory" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->path_name ?? $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" id="productStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <button type="button" class="btn btn-outline-secondary" id="productReload" title="Reload">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" id="productResults" data-total="{{ $products->total() }}">
        @include('admin.products.partials.results')
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('productFilterForm');
    var searchInput = document.getElementById('productSearch');
    var categorySelect = document.getElementById('productCategory');
    var statusSelect = document.getElementById('productStatus');
    var reloadBtn = document.getElementById('productReload');
    var results = document.getElementById('productResults');
    var totalBadge = document.getElementById('productTotalBadge');
    var indexUrl = @json(route('admin.products.index'));
    var debounceTimer = null;
    var abortController = null;

    function currentParams(page) {
        var params = new URLSearchParams();
        var search = searchInput.value.trim();
        if (search) params.set('search', search);
        if (categorySelect.value) params.set('category_id', categorySelect.value);
        if (statusSelect.value !== '') params.set('status', statusSelect.value);
        if (page) params.set('page', page);
        return params;
    }

    function loadProducts(page) {
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
            if (!response.ok) throw new Error('Failed to load products');
            var total = response.headers.get('X-Products-Total');
            return response.text().then(function (html) {
                return { html: html, total: total };
            });
        }).then(function (data) {
            results.innerHTML = data.html;
            results.style.opacity = '1';
            if (data.total !== null) {
                totalBadge.textContent = data.total;
                results.setAttribute('data-total', data.total);
            }
            history.replaceState(null, '', url);
        }).catch(function (error) {
            if (error.name === 'AbortError') return;
            results.style.opacity = '1';
        });
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        loadProducts();
    });

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            loadProducts();
        }, 300);
    });

    categorySelect.addEventListener('change', function () {
        loadProducts();
    });

    statusSelect.addEventListener('change', function () {
        loadProducts();
    });

    reloadBtn.addEventListener('click', function () {
        searchInput.value = '';
        categorySelect.value = '';
        statusSelect.value = '';
        loadProducts();
    });

    results.addEventListener('click', function (event) {
        var link = event.target.closest('#productPagination a');
        if (!link) return;
        event.preventDefault();
        var href = link.getAttribute('href');
        if (!href || href === '#') return;
        var page = new URL(href, window.location.origin).searchParams.get('page');
        loadProducts(page);
    });
});
</script>
@endpush
