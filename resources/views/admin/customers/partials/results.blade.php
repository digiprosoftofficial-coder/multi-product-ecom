@php
    $whatsappUrl = function (?string $phone): ?string {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '0')) {
            $digits = '88'.$digits;
        }

        return 'https://wa.me/'.$digits;
    };
@endphp

<div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th>Customer</th>
                <th>Contact</th>
                <th>Type</th>
                <th>Orders</th>
                <th>Last order</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                @php
                    $wa = $whatsappUrl($customer['phone'] ?? null);
                    $tel = preg_replace('/\s+/', '', (string) ($customer['phone'] ?? ''));
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $customer['name'] ?: '—' }}</div>
                        <div class="small text-muted">{{ $customer['email'] }}</div>
                    </td>
                    <td>
                        @if(! empty($customer['phone']))
                            <div>{{ $customer['phone'] }}</div>
                        @else
                            <span class="text-muted small">No phone</span>
                        @endif
                    </td>
                    <td>
                        @if($customer['is_registered'])
                            <span class="badge bg-success">Registered</span>
                        @else
                            <span class="badge bg-secondary">Guest</span>
                        @endif
                    </td>
                    <td>{{ $customer['orders_count'] }}</td>
                    <td class="small text-muted text-nowrap">
                        @if($customer['last_order_at'])
                            {{ \Illuminate\Support\Carbon::parse($customer['last_order_at'])->format('d M Y') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="mailto:{{ $customer['email'] }}" class="btn btn-sm btn-outline-primary" title="Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                        @if($tel)
                            <a href="tel:{{ $tel }}" class="btn btn-sm btn-outline-secondary" title="Call">
                                <i class="fas fa-phone"></i>
                            </a>
                        @endif
                        @if($wa)
                            <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                        @if($customer['orders_count'] > 0)
                            <a href="{{ route('admin.orders.index', ['search' => $customer['email']]) }}" class="btn btn-sm btn-outline-dark" title="Orders">
                                <i class="fas fa-shopping-bag"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($customers->hasPages())
    <div class="card-footer border-top-0" id="customerPagination">
        {{ $customers->links() }}
    </div>
@endif
