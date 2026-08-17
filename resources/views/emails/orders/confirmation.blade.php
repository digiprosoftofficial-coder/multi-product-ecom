<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order {{ $order->order_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="padding:24px 28px;background:#111827;color:#ffffff;">
                            <div style="font-size:18px;font-weight:bold;">{{ $siteName }}</div>
                            <div style="margin-top:6px;font-size:14px;">Order confirmation</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px;font-size:16px;">Hi {{ $order->customer_name }},</p>
                            <p style="margin:0 0 18px;color:#4b5563;">
                                Thank you for your order. We have received it and will contact you shortly for cash-on-delivery.
                            </p>
                            <p style="margin:0 0 18px;">
                                <strong>Order number:</strong> {{ $order->order_number }}<br>
                                <strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}<br>
                                <strong>Total:</strong> ${{ number_format($order->total, 2) }}
                            </p>

                            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
                                <tr style="background:#f9fafb;text-align:left;">
                                    <th style="border-bottom:1px solid #e5e7eb;">Item</th>
                                    <th style="border-bottom:1px solid #e5e7eb;">Qty</th>
                                    <th style="border-bottom:1px solid #e5e7eb;" align="right">Total</th>
                                </tr>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td style="border-bottom:1px solid #e5e7eb;">{{ $item->product_name }}</td>
                                        <td style="border-bottom:1px solid #e5e7eb;">{{ $item->quantity }}</td>
                                        <td style="border-bottom:1px solid #e5e7eb;" align="right">${{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <p style="margin:18px 0 0;">
                                <a href="{{ $invoiceUrl }}" style="display:inline-block;padding:10px 16px;background:#111827;color:#ffffff;text-decoration:none;border-radius:6px;">
                                    View / print invoice
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;color:#6b7280;font-size:12px;border-top:1px solid #e5e7eb;">
                            If you did not place this order, please ignore this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
