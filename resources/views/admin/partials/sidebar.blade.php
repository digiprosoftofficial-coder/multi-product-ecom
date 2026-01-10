<aside id="adminSidebar" class="bg-dark text-white sidebar-fixed" style="width: 250px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000; overflow-y: auto;">
    <div class="p-3 d-flex flex-column" style="min-height: 100vh;">
        <div>
            <h5 class="text-white mb-4">
                <i class="fas a-store"></i> Admin Panel
            </h5>
            <nav class="nav flex-column">
                <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'bg-primary' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-folder"></i> Categories
                </a>
                <a class="nav-link text-white {{ request()->routeIs('admin.subcategories.*') ? 'bg-primary' : '' }}" href="{{ route('admin.subcategories.index') }}">
                    <i class="fas fa-folder-open"></i> Subcategories
                </a>
                <a class="nav-link text-white {{ request()->routeIs('admin.childcategories.*') ? 'bg-primary' : '' }}" href="{{ route('admin.childcategories.index') }}">
                    <i class="fas fa-tags"></i> Child Categories
                </a>
                <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'bg-primary' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i> Products
                </a>
                <a class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'bg-primary' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
                <a class="nav-link text-white {{ request()->routeIs('admin.settings.*') ? 'bg-primary' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </nav>
        </div>

        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}" class="d-grid">
                @csrf
                <button type="submit" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
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

