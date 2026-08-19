<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales report {{ $from->format('Y-m-d') }} to {{ $to->format('Y-m-d') }}</title>
    <style>
        :root {
            --ink: #111827;
            --muted: #6b7280;
            --line: #e5e7eb;
            --accent: #111827;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--ink);
            background: #f3f4f6;
            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            font-size: 13px;
            line-height: 1.45;
        }
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            padding: 12px 16px;
            background: #fff;
            border-bottom: 1px solid var(--line);
        }
        .btn {
            display: inline-block;
            padding: 8px 14px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .sheet {
            width: 210mm;
            max-width: 100%;
            margin: 24px auto;
            padding: 28px 32px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,.06);
        }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--ink);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand img {
            max-height: 52px;
            max-width: 140px;
        }
        .brand h1 {
            margin: 0;
            font-size: 20px;
        }
        .meta { text-align: right; }
        .meta h2 {
            margin: 0 0 6px;
            font-size: 22px;
            letter-spacing: .06em;
        }
        .label {
            margin: 0 0 4px;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin: 20px 0;
        }
        .stat {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 12px;
        }
        .stat strong {
            display: block;
            font-size: 18px;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 6px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
        }
        th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--muted);
        }
        td.num, th.num { text-align: right; }
        .muted { color: var(--muted); font-size: 12px; }
        .footer {
            margin-top: 28px;
            color: var(--muted);
            font-size: 11px;
        }
        @media print {
            body { background: #fff; }
            .toolbar { display: none !important; }
            .sheet {
                margin: 0;
                box-shadow: none;
                width: auto;
            }
        }
        @page { margin: 12mm; size: A4 landscape; }
        @media (max-width: 800px) {
            .stats { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
        @if(!empty($backUrl))
            <a href="{{ $backUrl }}" class="btn">Back</a>
        @endif
    </div>

    <div class="sheet">
        <div class="header">
            <div class="brand">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}">
                @endif
                <div>
                    <h1>{{ $siteName }}</h1>
                    <div class="muted">Sales report</div>
                </div>
            </div>
            <div class="meta">
                <h2>REPORT</h2>
                <div><strong>{{ $from->format('M d, Y') }} – {{ $to->format('M d, Y') }}</strong></div>
                <div class="muted">{{ $periods[$period] ?? ucfirst(str_replace('_', ' ', $period)) }}</div>
                <div class="muted">Printed {{ $printedAt->format('M d, Y H:i') }}</div>
            </div>
        </div>

        <p class="muted" style="margin: 14px 0 0;">
            Payment: {{ $paymentStatus === 'all' ? 'All' : ucfirst($paymentStatus) }}
            · Order status: {{ $orderStatus === 'all' ? 'All' : ucfirst($orderStatus) }}
        </p>

        <div class="stats">
            <div class="stat">
                <p class="label">Sell</p>
                <strong>{{ $stats['sell'] }}</strong>
                <span class="muted">Orders excluding cancelled</span>
            </div>
            <div class="stat">
                <p class="label">Income</p>
                <strong>{{ money($stats['income']) }}</strong>
                <span class="muted">{{ $stats['paid_orders'] }} paid orders</span>
            </div>
            <div class="stat">
                <p class="label">Profit</p>
                <strong>{{ money($stats['profit']) }}</strong>
                <span class="muted">Selling minus purchase price</span>
            </div>
            <div class="stat">
                <p class="label">Paid orders</p>
                <strong>{{ $stats['paid_orders'] }}</strong>
                <span class="muted">Payment status = Paid</span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th class="num">Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>
                            {{ $order->customer_name }}
                            <div class="muted">{{ $order->customer_email }}</div>
                        </td>
                        <td>
                            @forelse($order->items as $item)
                                <div>
                                    {{ $item->product_name }}
                                    @if($item->quantity > 1)
                                        <span class="muted">× {{ $item->quantity }}</span>
                                    @endif
                                </div>
                            @empty
                                —
                            @endforelse
                        </td>
                        <td class="num">{{ money($order->total) }}</td>
                        <td>{{ ucfirst($order->payment_status) }}</td>
                        <td>{{ ucfirst($order->order_status) }}</td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted">No orders in this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($orders->count() >= 2000)
            <p class="muted">Showing the first 2,000 orders in this period.</p>
        @endif

        <div class="footer">
            Income and profit use paid orders only. Sell excludes cancelled orders.
            Use Print → Save as PDF to download this report.
        </div>
    </div>
</body>
</html>
