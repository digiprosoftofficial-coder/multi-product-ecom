<header class="bg-white shadow-sm border-bottom">
    <div class="d-flex justify-content-between align-items-center px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="#" class="position-relative text-muted" title="Notifications">
                <i class="fas fa-bell"></i>
                @php $unread = Auth::user()?->unreadNotifications()->count() ?? 0; @endphp
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="admin-notif-count" style="display: {{ $unread ? 'inline-block' : 'none' }};">
                    {{ $unread }}
                </span>
            </a>
            <span class="text-muted">{{ Auth::user()->name }}</span>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Site
            </a>
        </div>
    </div>
</header>

