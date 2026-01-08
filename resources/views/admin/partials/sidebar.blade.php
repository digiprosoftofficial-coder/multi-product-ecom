<aside class="bg-dark text-white" style="width: 250px; min-height: 100vh;">
    <div class="p-3">
        <h5 class="text-white mb-4">
            <i class="fas fa-store"></i> Admin Panel
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
</aside>

<style>
.wrapper {
    min-height: 100vh;
}
.main-content {
    background-color: #f8f9fa;
}
.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}
</style>

