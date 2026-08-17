@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Shopping Cart</h2>

    @if(count($cartItems) > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item['product']->thumbnail)
                                        <img src="{{ asset('uploads/products/thumbnails/' . $item['product']->thumbnail) }}" 
                                             alt="{{ $item['product']->name }}" 
                                             style="width: 80px; height: 80px; object-fit: cover;" 
                                             class="me-3">
                                    @endif
                                    <div>
                                        <h6>{{ $item['product']->name }}</h6>
                                        <small class="text-muted">SKU: {{ $item['product']->sku }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item['product']->final_price, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" 
                                           value="{{ $item['quantity'] }}" 
                                           min="1" 
                                           max="{{ $item['product']->stock }}"
                                           class="form-control form-control-sm" 
                                           style="width: 80px;"
                                           onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>${{ number_format($item['subtotal'], 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item['product']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>${{ number_format($total, 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Continue Shopping</a>
            <div class="d-flex gap-2">
                <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to clear the cart?')">
                        Clear Cart
                    </button>
                </form>
                @auth
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">Proceed to Checkout</a>
                @else
                    <a href="{{ route('checkout.index') }}" class="btn btn-outline-primary btn-lg">Guest Checkout</a>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login &amp; Checkout</a>
                @endauth
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
            <h4>Your cart is empty</h4>
            <p class="text-muted">Start shopping to add items to your cart.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Products</a>
        </div>
    @endif
</div>
@endsection

