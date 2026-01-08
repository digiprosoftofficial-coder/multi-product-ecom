<div class="card h-100 shadow-sm">
    @if($category->image)
        <img src="{{ asset('uploads/categories/thumbnails/' . $category->image) }}" 
             class="card-img-top" 
             alt="{{ $category->name }}"
             style="height: 200px; object-fit: cover;">
    @else
        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <i class="fas fa-folder fa-3x text-muted"></i>
        </div>
    @endif
    
    <div class="card-body">
        <h5 class="card-title">{{ $category->name }}</h5>
        @if($category->description)
            <p class="card-text text-muted small">{{ Str::limit($category->description, 100) }}</p>
        @endif
        
        @if($category->subCategories->count() > 0)
            <p class="text-muted small mb-2">
                <i class="fas fa-tags"></i> {{ $category->subCategories->count() }} subcategories
            </p>
        @endif
        
        <a href="{{ route('products.category', $category) }}" class="btn btn-primary btn-sm">
            View Products
        </a>
    </div>
</div>

