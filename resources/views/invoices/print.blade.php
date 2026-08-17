<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }}</title>
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
            font-size: 14px;
            line-height: 1.5;
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
            padding: 32px 36px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,.06);
        }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--ink);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand img {
            max-height: 56px;
            max-width: 140px;
        }
        .brand h1 {
            margin: 0;
            font-size: 22px;
        }
        .meta { text-align: right; }
        .meta h2 {
            margin: 0 0 6px;
            font-size: 28px;
            letter-spacing: .08em;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin: 24px 0;
        }
        .label {
            margin: 0 0 6px;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px 8px;
            border-bottom: 1px solid var(--line);
            text-align: left;
        }
        th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--muted);
        }
        td.num, th.num { text-align: right; }
        .totals {
            width: 280px;
            margin-left: auto;
            margin-top: 16px;
        }
        .totals td { border: 0; padding: 6px 0; }
        .totals .grand td {
            padding-top: 10px;
            border-top: 2px solid var(--ink);
            font-size: 16px;
            font-weight: 700;
        }
        .notes {
            margin-top: 28px;
            padding-top: 16px;
            border-top: 1px solid var(--line);
            color: var(--muted);
        }
        .footer {
            margin-top: 36px;
            color: var(--muted);
            font-size: 12px;
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
        @page { margin: 12mm; }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <button type="button" class="btn btn-primary" onclick="window.print()">Print invoice</button>
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
                    <div style="color:var(--muted)">Cash on Delivery</div>
                </div>
            </div>
            <div class="meta">
                <h2>INVOICE</h2>
                <div><strong>{{ $order->order_number }}</strong></div>
                <div>{{ $order->created_at->format('M d, Y H:i') }}</div>
            </div>
        </div>

        <div class="grid">
            <div>
                <p class="label">Bill to</p>
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_email }}<br>
                @if($order->customer_phone)
                    {{ $order->customer_phone }}<br>
                @endif
            </div>
            <div>
                <p class="label">Ship to</p>
                {!! nl2br(e($order->shipping_address)) !!}
            </div>
        </div>

        <div class="grid">
            <div>
                <p class="label">Order status</p>
                {{ ucfirst($order->order_status) }}
            </div>
            <div>
                <p class="label">Payment</p>
                {{ $order->paymentMethodLabel() }} — {{ ucfirst($order->payment_status) }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>SKU</th>
                    <th class="num">Qty</th>
                    <th class="num">Price</th>
                    <th class="num">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->product_sku }}</td>
                        <td class="num">{{ $item->quantity }}</td>
                        <td class="num">${{ number_format($item->price, 2) }}</td>
                        <td class="num">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td class="num">${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax > 0)
                <tr>
                    <td>Tax</td>
                    <td class="num">${{ number_format($order->tax, 2) }}</td>
                </tr>
            @endif
            @if($order->vat > 0)
                <tr>
                    <td>VAT</td>
                    <td class="num">${{ number_format($order->vat, 2) }}</td>
                </tr>
            @endif
            <tr class="grand">
                <td>Total due</td>
                <td class="num">${{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        @if($order->notes)
            <div class="notes">
                <p class="label">Order notes</p>
                {!! nl2br(e($order->notes)) !!}
            </div>
        @endif

        <div class="footer">
            Thank you for your order. Please keep this invoice for your records.
        </div>
    </div>
</body>
</html>
