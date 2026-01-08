@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container my-5">
    @include('frontend.components.breadcrumb', [
        'items' => [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Categories', 'url' => route('products.index')],
            ['name' => $category->name, 'url' => null],
        ]
    ])

    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="mb-0">{{ $category->name }}</h2>

        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="subCatDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                Subcategories ({{ $subCategories->count() }})
            </button>
            <ul class="dropdown-menu" aria-labelledby="subCatDropdown">
                @forelse($subCategories as $subCategory)
                    <li>
                        <a class="dropdown-item" href="{{ route('products.subcategory', $subCategory) }}">
                            {{ $subCategory->name }}
                        </a>
                    </li>
                @empty
                    <li><span class="dropdown-item text-muted">No subcategories</span></li>
                @endforelse
            </ul>
        </div>
    </div>

    @if($subCategories->count() > 0)
        <div class="mb-4">
            <h5>Sub Categories</h5>
            <div class="row">
                @foreach($subCategories as $subCategory)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('products.subcategory', $subCategory) }}" class="text-decoration-none">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6>{{ $subCategory->name }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                @include('frontend.components.product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No products found in this category.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection

