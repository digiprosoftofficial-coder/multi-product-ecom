<header class="site-header auth-header">
    <div class="container-lg">
        <div class="d-flex align-items-center justify-content-between gap-3 py-3">
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                @if(site_logo_url())
                    <img src="{{ site_logo_url() }}" alt="{{ site_name() }}" class="img-fluid" style="max-height: 42px; width: auto;">
                @else
                    <span class="fw-bold fs-5 text-dark">{{ site_name() }}</span>
                @endif
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm rounded-1 text-nowrap">
                Continue shopping
            </a>
        </div>
    </div>
</header>
