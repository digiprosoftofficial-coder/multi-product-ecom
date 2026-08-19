<aside id="adminSidebar" class="bg-dark text-white sidebar-fixed" style="width: 250px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000; overflow-y: auto;">
    <div class="p-3 d-flex flex-column" style="min-height: 100vh;">
        <div>
            <h5 class="text-white mb-4 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-store"></i> {{ site_name() }}</span>
            </h5>
            <nav class="nav flex-column">
            <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'bg-primary' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="fas fa-folder"></i> Categories
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'bg-primary' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="fas fa-box"></i> Products
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'bg-primary' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.reports.*') ? 'bg-primary' : '' }}" href="{{ route('admin.reports.index') }}">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.homepage.*') ? 'bg-primary' : '' }}" href="{{ route('admin.homepage.index') }}">
                <i class="fas fa-home"></i> Homepage
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.pages.*') ? 'bg-primary' : '' }}" href="{{ route('admin.pages.index') }}">
                <i class="fas fa-file-alt"></i> Pages
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.settings.*') ? 'bg-primary' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a class="nav-link text-white {{ request()->routeIs('admin.themes.*') ? 'bg-primary' : '' }}" href="{{ route('admin.themes.index') }}">
                <i class="fas fa-palette"></i> Themes
            </a>
            </nav>
        </div>

        <div class="mt-auto pt-3 border-top border-secondary">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="fas fa-user-circle fs-4"></i>
                <div>
                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                    <small >{{ Auth::user()->email }}</small>
                </div>
            </div>
            <div class="d-grid">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light mb-2">
                    <i class="fas fa-user"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger w-100">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<style>
.wrapper {
    min-height: 100vh;
}
.sidebar-fixed {
    width: 250px !important;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.main-content {
    background-color: #f8f9fa;
    margin-left: 250px;
    width: calc(100% - 250px);
}
.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}
@media (max-width: 768px) {
    .sidebar-fixed {
        transform: translateX(-100%);
    }
    .sidebar-fixed.active {
        transform: translateX(0);
    }
    .main-content {
        margin-left: 0;
        width: 100%;
    }
}
</style>

