<div class="card h-100 shadow-sm">
    @if($product->thumbnail)
        <img src="{{ asset('uploads/products/thumbnails/' . $product->thumbnail) }}" 
             class="card-img-top" 
             alt="{{ $product->name }}"
             style="height: 200px; object-fit: cover;">
    @else
        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <span class="text-muted">No Image</span>
        </div>
    @endif
    
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $product->name }}</h5>
        <p class="card-text text-muted small">{{ Str::limit($product->description, 100) }}</p>
        
        <div class="mt-auto">
            <div class="mb-2">
                @if($product->discount_price)
                    <span class="text-danger fw-bold fs-5">${{ number_format($product->discount_price, 2) }}</span>
                    <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                @else
                    <span class="fw-bold fs-5">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm flex-grow-1">
                    View Details
                </a>
                @if($product->isInStock())
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline js-add-to-cart"
                          data-product-id="{{ $product->id }}"
                          data-product-name="{{ $product->name }}"
                          data-product-image="{{ $product->thumbnail ? asset('uploads/products/thumbnails/' . $product->thumbnail) : '' }}">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-sm" disabled>Out of Stock</button>
                @endif
            </div>
        </div>
    </div>
</div>

