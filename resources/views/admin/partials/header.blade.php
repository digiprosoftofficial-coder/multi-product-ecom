<header class="bg-white shadow-sm border-bottom">
    <div class="d-flex justify-content-between align-items-center px-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            @php
                $adminRecentOrders = $adminRecentOrders ?? collect();
                $pendingCount = $adminRecentOrders->where('order_status', 'pending')->count();
            @endphp
            <div class="dropdown">
                <button class="btn btn-link text-muted position-relative dropdown-toggle p-0"
                        type="button"
                        id="notifDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                          id="admin-notif-count"
                          style="display: {{ $pendingCount > 0 ? 'inline-block' : 'none' }};">
                        {{ $pendingCount }}
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="min-width: 280px;">
                    <li class="dropdown-header d-flex justify-content-between">
                        <span>Recent Orders</span>
                        <span class="badge bg-secondary">{{ $pendingCount }} pending</span>
                    </li>
                    @forelse($adminRecentOrders as $order)
                        <li>
                            <a class="dropdown-item d-flex justify-content-between align-items-start"
                               href="{{ route('admin.orders.show', $order) }}">
                                <div>
                                    <div class="fw-semibold">Order: {{ $order->order_number }}</div>
                                    <small class="text-muted">
                                        {{ $order->customer_name }} · {{ money($order->total) }}
                                    </small>
                                </div>
                                <small class="text-muted ms-2">{{ $order->created_at->diffForHumans() }}</small>
                            </a>
                        </li>
                    @empty
                        <li><span class="dropdown-item text-muted">No recent orders</span></li>
                    @endforelse
                </ul>
            </div>
            <span class="text-muted">{{ Auth::user()->name }}</span>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Site
            </a>
        </div>
    </div>
</header>

