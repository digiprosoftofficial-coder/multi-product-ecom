@extends('admin.layouts.master')

@section('title', 'New sale')
@section('page-title', 'New sale')

@php
    $zone = old('delivery_zone', 'inside_dhaka');
    $shippingDefault = $zone === 'outside_dhaka' ? $shippingOutside : $shippingInside;
    $method = old('payment_method', 'cash_on_delivery');
@endphp

@section('content')
<form action="{{ route('admin.orders.store') }}" method="POST" id="sale-form">
    @csrf
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to orders</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check me-1"></i> Save sale
        </button>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Customer</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" placeholder="01XXXXXXXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email') }}">
                        </div>
                        <div class="col-12">
                            <label for="shipping_address" class="form-label">Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="2" class="form-control @error('shipping_address') is-invalid @enderror" required>{{ old('shipping_address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Products</h5></div>
                <div class="card-body">
                    <label for="sale-search" class="form-label">Add product</label>
                    <input type="search" id="sale-search" class="form-control" placeholder="Search name or SKU" autocomplete="off">
                    <div id="sale-results" class="list-group mt-2 d-none"></div>
                    <div id="sale-lines" class="mt-3"></div>
                    <p id="sale-empty" class="text-muted mb-0 mt-3">No products added yet.</p>
                    @error('items')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Notes</h5></div>
                <div class="card-body">
                    <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Phone or WhatsApp">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Delivery and payment</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="delivery_zone" class="form-label">Delivery area</label>
                        <select name="delivery_zone" id="delivery_zone" class="form-select" required>
                            <option value="inside_dhaka" @selected($zone === 'inside_dhaka')>Inside Dhaka ({{ money($shippingInside) }})</option>
                            <option value="outside_dhaka" @selected($zone === 'outside_dhaka')>Outside Dhaka ({{ money($shippingOutside) }})</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_cost" class="form-label">Delivery charge</label>
                        <input type="number" name="shipping_cost" id="shipping_cost" min="0" step="1" class="form-control" value="{{ old('shipping_cost', $shippingDefault) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment method</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            @foreach($methods as $key => $item)
                                <option value="{{ $key }}" @selected($method === $key)>{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment status</label>
                        <select name="payment_status" id="payment_status" class="form-select" required>
                            <option value="pending" @selected(old('payment_status', 'pending') === 'pending')>Pending</option>
                            <option value="paid" @selected(old('payment_status') === 'paid')>Paid</option>
                        </select>
                    </div>
                    <div id="wallet-fields" class="d-none">
                        <div class="mb-3">
                            <label for="payment_reference" class="form-label">Transaction ID</label>
                            <input type="text" name="payment_reference" id="payment_reference" class="form-control" value="{{ old('payment_reference') }}">
                        </div>
                        <div class="mb-0">
                            <label for="payment_sender_phone" class="form-label">Sender number</label>
                            <input type="text" name="payment_sender_phone" id="payment_sender_phone" class="form-control" value="{{ old('payment_sender_phone') }}" placeholder="01XXXXXXXXX">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Total</h5></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong data-subtotal></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tax</span><strong data-tax></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>VAT</span><strong data-vat></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Delivery</span><strong data-shipping></strong></div>
                    <hr>
                    <div class="d-flex justify-content-between"><span>Total</span><strong data-grand></strong></div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save sale</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    #sale-results { max-height: 240px; overflow: auto; }
    .sale-line {
        display: grid;
        grid-template-columns: 1fr 110px 90px auto;
        gap: .5rem;
        align-items: center;
        padding: .75rem 0;
        border-top: 1px solid #e8eee9;
    }
    .sale-line:first-child { border-top: 0; padding-top: 0; }
    @media (max-width: 767px) {
        .sale-line { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var catalog = @json($catalog);
    var oldItems = @json(old('items', []));
    var taxRate = {{ $taxRate }};
    var vatRate = {{ $vatRate }};
    var shippingInside = {{ $shippingInside }};
    var shippingOutside = {{ $shippingOutside }};
    var symbol = @json(currency_symbol());
    var lines = [];
    var shippingTouched = @json(old('shipping_cost') !== null);

    var search = document.getElementById('sale-search');
    var results = document.getElementById('sale-results');
    var list = document.getElementById('sale-lines');
    var empty = document.getElementById('sale-empty');
    var form = document.getElementById('sale-form');
    var zone = document.getElementById('delivery_zone');
    var shipping = document.getElementById('shipping_cost');
    var method = document.getElementById('payment_method');
    var wallet = document.getElementById('wallet-fields');

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (ch) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[ch];
        });
    }

    function taka(amount) {
        return Math.round(Number(amount) || 0);
    }

    function money(amount) {
        return symbol + taka(amount).toLocaleString('en-US');
    }

    function findProduct(id) {
        return catalog.find(function (item) { return String(item.id) === String(id); });
    }

    function stockFor(line) {
        if (line.product.has_variants) {
            var variant = (line.product.variants || []).find(function (item) {
                return String(item.id) === String(line.variant_id);
            });
            return variant ? variant.stock : 0;
        }
        return line.product.stock;
    }

    function addProduct(product, variantId) {
        var existing = lines.find(function (line) {
            return line.product.id === product.id && String(line.variant_id || '') === String(variantId || '');
        });
        if (existing) {
            existing.quantity += 1;
        } else {
            var variant = product.has_variants
                ? (product.variants.find(function (item) { return String(item.id) === String(variantId); }) || product.variants.find(function (item) { return item.stock > 0; }) || product.variants[0])
                : null;
            lines.push({
                product: product,
                variant_id: variant ? variant.id : '',
                quantity: 1,
                price: product.price
            });
        }
        search.value = '';
        results.classList.add('d-none');
        render();
    }

    function renderResults() {
        var term = search.value.trim().toLowerCase();
        results.innerHTML = '';
        if (term.length < 1) {
            results.classList.add('d-none');
            return;
        }
        var matches = catalog.filter(function (product) {
            return product.name.toLowerCase().indexOf(term) !== -1
                || String(product.sku || '').toLowerCase().indexOf(term) !== -1;
        }).slice(0, 8);
        if (!matches.length) {
            results.classList.add('d-none');
            return;
        }
        matches.forEach(function (product) {
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'list-group-item list-group-item-action';
            button.textContent = product.name + ' · ' + money(product.price) + ' · stock ' + product.stock;
            button.addEventListener('click', function () { addProduct(product); });
            results.appendChild(button);
        });
        results.classList.remove('d-none');
    }

    function render() {
        list.innerHTML = '';
        empty.classList.toggle('d-none', lines.length > 0);
        var subtotal = 0;
        lines.forEach(function (line, index) {
            var row = document.createElement('div');
            row.className = 'sale-line';
            var maxStock = Math.max(1, stockFor(line));
            var variantHtml = '';
            if (line.product.has_variants) {
                variantHtml = '<select class="form-select form-select-sm" data-variant>' +
                    line.product.variants.map(function (variant) {
                        return '<option value="' + variant.id + '"' + (String(variant.id) === String(line.variant_id) ? ' selected' : '') + '>' +
                            escapeHtml(variant.label) + ' (' + variant.stock + ')</option>';
                    }).join('') + '</select>';
            }
            row.innerHTML =
                '<div><div class="fw-semibold">' + escapeHtml(line.product.name) + '</div><div class="small text-muted">' + escapeHtml(line.product.sku || '') + '</div>' + variantHtml +
                '<input type="hidden" name="items[' + index + '][product_id]" value="' + line.product.id + '">' +
                '<input type="hidden" name="items[' + index + '][variant_id]" value="' + (line.variant_id || '') + '" data-variant-input>' +
                '</div>' +
                '<input type="number" class="form-control" name="items[' + index + '][price]" min="0" step="1" value="' + line.price + '" data-price>' +
                '<input type="number" class="form-control" name="items[' + index + '][quantity]" min="1" max="' + maxStock + '" value="' + line.quantity + '" data-qty>' +
                '<div class="text-end"><div data-line-total>' + money(line.price * line.quantity) + '</div>' +
                '<button type="button" class="btn btn-sm btn-outline-danger mt-1" data-remove>Remove</button></div>';
            list.appendChild(row);
            subtotal += taka(line.price) * line.quantity;

            row.querySelector('[data-price]').addEventListener('input', function (event) {
                line.price = taka(event.target.value);
                refreshTotals();
                row.querySelector('[data-line-total]').textContent = money(line.price * line.quantity);
            });
            row.querySelector('[data-qty]').addEventListener('input', function (event) {
                line.quantity = Math.max(1, parseInt(event.target.value, 10) || 1);
                refreshTotals();
                row.querySelector('[data-line-total]').textContent = money(line.price * line.quantity);
            });
            var variantSelect = row.querySelector('[data-variant]');
            if (variantSelect) {
                variantSelect.addEventListener('change', function () {
                    line.variant_id = variantSelect.value;
                    row.querySelector('[data-variant-input]').value = line.variant_id;
                    var qty = row.querySelector('[data-qty]');
                    qty.max = Math.max(1, stockFor(line));
                });
            }
            row.querySelector('[data-remove]').addEventListener('click', function () {
                lines.splice(index, 1);
                render();
            });
        });
        syncDefaultShipping();
        refreshTotals(subtotal);
    }

    function allFreeDelivery() {
        return lines.length > 0 && lines.every(function (line) { return line.product.free_delivery; });
    }

    function syncDefaultShipping() {
        if (shippingTouched) return;
        shipping.value = allFreeDelivery()
            ? 0
            : (zone.value === 'outside_dhaka' ? shippingOutside : shippingInside);
    }

    function refreshTotals(subtotal) {
        if (subtotal === undefined) {
            subtotal = lines.reduce(function (sum, line) {
                return sum + (taka(line.price) * line.quantity);
            }, 0);
        }
        var tax = taka(subtotal * taxRate / 100);
        var vat = taka(subtotal * vatRate / 100);
        var delivery = taka(shipping.value);
        form.querySelector('[data-subtotal]').textContent = money(subtotal);
        form.querySelector('[data-tax]').textContent = money(tax);
        form.querySelector('[data-vat]').textContent = money(vat);
        form.querySelector('[data-shipping]').textContent = money(delivery);
        form.querySelector('[data-grand]').textContent = money(subtotal + tax + vat + delivery);
    }

    function toggleWallet() {
        var isWallet = ['bkash', 'nagad', 'rocket'].indexOf(method.value) !== -1;
        wallet.classList.toggle('d-none', !isWallet);
    }

    search.addEventListener('input', renderResults);
    shipping.addEventListener('input', function () {
        shippingTouched = true;
        refreshTotals();
    });
    zone.addEventListener('change', function () {
        syncDefaultShipping();
        refreshTotals();
    });
    method.addEventListener('change', toggleWallet);

    oldItems.forEach(function (item) {
        var product = findProduct(item.product_id);
        if (!product) return;
        lines.push({
            product: product,
            variant_id: item.variant_id || '',
            quantity: Math.max(1, parseInt(item.quantity, 10) || 1),
            price: taka(item.price)
        });
    });

    toggleWallet();
    render();
})();
</script>
@endpush
