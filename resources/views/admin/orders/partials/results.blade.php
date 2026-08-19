<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order Number</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>
                        <div>{{ $order->customer_name }}</div>
                        <small class="text-muted">{{ $order->customer_email }}</small>
                    </td>
                    <td>
                        @forelse($order->items as $item)
                            <div>
                                {{ $item->product_name }}
                                @if($item->quantity > 1)
                                    <small class="text-muted">× {{ $item->quantity }}</small>
                                @endif
                            </div>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                    </td>
                    <td>{{ money($order->total) }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status_badge }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                View
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-sm btn-outline-secondary">
                                Invoice
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3" id="orderPagination">
    {{ $orders->links() }}
</div>
