<aside id="adminSidebar" class="admin-sidebar sidebar-fixed">
    <div class="sidebar-inner d-flex flex-column">
        <div>
            <div class="sidebar-brand">
                <span class="brand-mark"><i class="fas fa-store"></i></span>
                <span class="brand-name">{{ site_name() }}</span>
            </div>
            <nav class="nav flex-column sidebar-nav">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-folder"></i> Categories
                </a>
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i> Products
                </a>
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
                <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'is-active' : '' }}" href="{{ route('admin.customers.index') }}">
                    <i class="fas fa-users"></i> Customers
                </a>
                <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.contact-messages.*') ? 'is-active' : '' }}" href="{{ route('admin.contact-messages.index') }}">
                    <span><i class="fas fa-inbox"></i> Messages</span>
                    @php $unreadContactMessages = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                    @if($unreadContactMessages > 0)
                        <span class="badge rounded-pill bg-success">{{ $unreadContactMessages }}</span>
                    @endif
                </a>
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
                @php
                    $siteSettingOpen = request()->routeIs('admin.homepage.*')
                        || request()->routeIs('admin.about.*')
                        || request()->routeIs('admin.shop-page.*')
                        || request()->routeIs('admin.contact-page.*')
                        || request()->routeIs('admin.pages.*');
                @endphp
                <a class="nav-link nav-parent d-flex justify-content-between align-items-center {{ $siteSettingOpen ? 'is-open' : '' }}"
                   data-bs-toggle="collapse"
                   href="#siteSettingMenu"
                   role="button"
                   aria-expanded="{{ $siteSettingOpen ? 'true' : 'false' }}"
                   aria-controls="siteSettingMenu">
                    <span><i class="fas fa-globe"></i> Site Setting</span>
                    <i class="fas fa-chevron-down nav-chevron small"></i>
                </a>
                <div class="collapse {{ $siteSettingOpen ? 'show' : '' }}" id="siteSettingMenu">
                    <nav class="nav flex-column sidebar-subnav">
                        <a class="nav-link {{ request()->routeIs('admin.homepage.*') ? 'is-active' : '' }}" href="{{ route('admin.homepage.index') }}">
                            <i class="fas fa-home"></i> Homepage
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.about.*') ? 'is-active' : '' }}" href="{{ route('admin.about.index') }}">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.shop-page.*') ? 'is-active' : '' }}" href="{{ route('admin.shop-page.index') }}">
                            <i class="fas fa-store"></i> Shop
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.contact-page.*') ? 'is-active' : '' }}" href="{{ route('admin.contact-page.index') }}">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'is-active' : '' }}" href="{{ route('admin.pages.index') }}">
                            <i class="fas fa-file-alt"></i> Legal pages
                        </a>
                    </nav>
                </div>
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
                {{-- Themes hidden for this version --}}
            </nav>
        </div>

        <div class="sidebar-footer mt-auto">
            <div class="sidebar-user">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-meta">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
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
    width: 260px !important;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.admin-sidebar {
    background: #f4f7f5;
    border-right: 1px solid #e2ebe5;
    color: #1f2937;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    overflow-y: auto;
    min-height: 100vh;
}
.sidebar-inner {
    min-height: 100vh;
    padding: 1.15rem 1rem 1rem;
}
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: .7rem;
    margin-bottom: 1.35rem;
    padding: 0 .35rem;
}
.brand-mark {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #16a34a;
    color: #fff;
    font-size: .95rem;
    flex-shrink: 0;
}
.brand-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
}
.main-content {
    background-color: #f8faf9;
    margin-left: 260px;
    width: calc(100% - 260px);
}
.sidebar-nav > .nav-link {
    color: #475569;
    border-radius: .6rem;
    margin-bottom: .18rem;
    padding: .58rem .75rem;
    font-weight: 500;
    transition: background-color .15s ease, color .15s ease;
}
.sidebar-nav > .nav-link i {
    width: 1.15rem;
    text-align: center;
    margin-right: .4rem;
    color: #64748b;
}
.sidebar-nav > .nav-link:hover {
    background-color: #e8f2ec;
    color: #0f172a;
}
.sidebar-nav > .nav-link:hover i {
    color: #16a34a;
}
.sidebar-nav > .nav-link.is-active {
    background: #dcfce7;
    color: #14532d;
    box-shadow: inset 3px 0 0 #16a34a;
}
.sidebar-nav > .nav-link.is-active i {
    color: #16a34a;
}
.sidebar-nav > .nav-link.nav-parent.is-open {
    background: #eef5f1;
    color: #0f172a;
}
.sidebar-nav > .nav-link.nav-parent .nav-chevron {
    transition: transform .2s ease;
    opacity: .55;
}
.sidebar-nav > .nav-link.nav-parent[aria-expanded="true"] .nav-chevron,
.sidebar-nav > .nav-link.nav-parent.is-open .nav-chevron {
    transform: rotate(180deg);
}
.sidebar-subnav {
    margin: .1rem 0 .4rem .85rem;
    padding-left: .6rem;
    border-left: 1px solid #d7e5dc;
}
.sidebar-subnav .nav-link {
    color: #64748b;
    border-radius: .5rem;
    margin-bottom: .12rem;
    padding: .42rem .7rem;
    font-size: .94rem;
    font-weight: 500;
}
.sidebar-subnav .nav-link i {
    width: 1.05rem;
    text-align: center;
    margin-right: .3rem;
    color: #94a3b8;
}
.sidebar-subnav .nav-link:hover {
    background-color: #e8f2ec;
    color: #0f172a;
}
.sidebar-subnav .nav-link.is-active {
    background: #dcfce7;
    color: #14532d;
    box-shadow: inset 3px 0 0 #22c55e;
}
.sidebar-subnav .nav-link.is-active i {
    color: #16a34a;
}
.sidebar-footer {
    padding-top: 1rem;
    border-top: 1px solid #dde8e1;
}
.sidebar-user {
    display: flex;
    align-items: center;
    gap: .7rem;
    margin-bottom: .75rem;
}
.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #dcfce7;
    color: #166534;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: .9rem;
    flex-shrink: 0;
}
.user-name {
    font-weight: 600;
    font-size: .92rem;
    color: #0f172a;
    line-height: 1.2;
}
.user-email {
    font-size: .75rem;
    color: #64748b;
    line-height: 1.2;
    word-break: break-all;
}
.btn-logout {
    width: 100%;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    border-radius: .55rem;
    padding: .45rem .7rem;
    font-size: .88rem;
    font-weight: 500;
    transition: all .15s ease;
}
.btn-logout:hover {
    border-color: #fecaca;
    background: #fef2f2;
    color: #b91c1c;
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
