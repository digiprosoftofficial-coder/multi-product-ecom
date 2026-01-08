<header class="bg-white shadow-sm border-bottom">
    <div class="d-flex justify-content-between align-items-center px-4 py-3">
        <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted">{{ Auth::user()->name }}</span>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Site
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>

