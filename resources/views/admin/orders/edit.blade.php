@extends('admin.layouts.master')

@section('title', 'Edit Order')
@section('page-title', 'Edit order #' . $order->order_number)

@php
    $oldSubtotal = (float) $order->subtotal;
    $taxRatio = $oldSubtotal > 0 ? ((float) $order->tax) / $oldSubtotal : 0;
    $vatRatio = $oldSubtotal > 0 ? ((float) $order->vat) / $oldSubtotal : 0;
    $zone = old('delivery_zone', $order->delivery_zone);
@endphp

@section('content')
<form action="{{ route('admin.orders.update', $order) }}" method="POST" id="order-edit-form">
    @csrf
    @method('PUT')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">Back to order</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Save order
        </button>
    </div>

    @if($order->order_status === 'cancelled')
        <div class="alert alert-warning">This order is cancelled, so changing quantity will not change stock.</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Customer</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $order->customer_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', $order->customer_phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', $order->customer_email) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Delivery</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Address</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="delivery_zone" class="form-label">Delivery area</label>
                            <select name="delivery_zone" id="delivery_zone" class="form-select @error('delivery_zone') is-invalid @enderror" required>
                                <option value="inside_dhaka" @selected($zone === 'inside_dhaka')>Inside Dhaka ({{ money($shippingInside) }})</option>
                                <option value="outside_dhaka" @selected($zone === 'outside_dhaka')>Outside Dhaka ({{ money($shippingOutside) }})</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="shipping_cost" class="form-label">Delivery charge</label>
                            <input type="number" name="shipping_cost" id="shipping_cost" min="0" step="1" class="form-control @error('shipping_cost') is-invalid @enderror" value="{{ old('shipping_cost', taka($order->shipping_cost)) }}" required>
                        </div>
                    </div>
                    <div class="form-text mt-2">Changing the area does not overwrite the charge. Set the charge you want to collect.</div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Products</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th style="width: 120px;">Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    @php $qty = old('items.'.$item->id.'.quantity', $item->quantity); @endphp
                                    <tr data-line data-price="{{ taka($item->price) }}">
                                        <td>
                                            {{ $item->product_name }}
                                            @if($item->variant_label)
                                                <div class="small text-muted">{{ $item->variant_label }}</div>
                                            @endif
                                            <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                                        </td>
                                        <td>{{ money($item->price) }}</td>
                                        <td>
                                            <input type="number" name="items[{{ $item->id }}][quantity]" class="form-control" min="1" max="999" value="{{ $qty }}" required data-qty>
                                        </td>
                                        <td class="text-end" data-line-total>{{ money(taka($item->price) * (int) $qty) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Notes</h5></div>
                <div class="card-body">
                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Order total</h5></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong data-subtotal></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tax</span><strong data-tax></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>VAT</span><strong data-vat></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Delivery</span><strong data-shipping></strong></div>
                    <hr>
                    <div class="d-flex justify-content-between"><span>Total</span><strong data-grand></strong></div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Save order</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    var symbol = @json(currency_symbol());
    var taxRatio = {{ $taxRatio }};
    var vatRatio = {{ $vatRatio }};
    var form = document.getElementById('order-edit-form');
    if (!form) return;

    function taka(amount) {
        return Math.round(Number(amount) || 0);
    }

    function money(amount) {
        return symbol + taka(amount).toLocaleString('en-US');
    }

    function refresh() {
        var subtotal = 0;
        form.querySelectorAll('[data-line]').forEach(function (row) {
            var price = Number(row.getAttribute('data-price')) || 0;
            var qty = Math.max(1, parseInt(row.querySelector('[data-qty]').value, 10) || 1);
            var line = price * qty;
            subtotal += line;
            row.querySelector('[data-line-total]').textContent = money(line);
        });
        var shipping = taka(document.getElementById('shipping_cost').value);
        var tax = taka(subtotal * taxRatio);
        var vat = taka(subtotal * vatRatio);
        form.querySelector('[data-subtotal]').textContent = money(subtotal);
        form.querySelector('[data-tax]').textContent = money(tax);
        form.querySelector('[data-vat]').textContent = money(vat);
        form.querySelector('[data-shipping]').textContent = money(shipping);
        form.querySelector('[data-grand]').textContent = money(subtotal + tax + vat + shipping);
    }

    form.addEventListener('input', refresh);
    refresh();
})();
</script>
@endpush
