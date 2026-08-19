<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Support\SalesReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->reportData($request, paginate: true);

        return view('admin.reports.index', $data);
    }

    public function print(Request $request)
    {
        $data = $this->reportData($request, paginate: false);
        $data['siteName'] = site_name();
        $data['logoUrl'] = site_logo_url();
        $data['backUrl'] = route('admin.reports.index', $request->query());
        $data['printedAt'] = now();

        return view('admin.reports.print', $data);
    }

    private function reportData(Request $request, bool $paginate): array
    {
        $periods = SalesReport::periods();
        $period = $request->input('period', 'today');
        if (! array_key_exists($period, $periods)) {
            $period = 'today';
        }

        [$from, $to] = SalesReport::range(
            $period,
            $request->input('from'),
            $request->input('to')
        );

        $paymentStatus = $request->input('payment_status', 'all');
        $orderStatus = $request->input('order_status', 'all');
        $allowedPayments = ['all', 'paid', 'pending', 'cancelled', 'refunded'];
        $allowedStatuses = ['all', 'pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (! in_array($paymentStatus, $allowedPayments, true)) {
            $paymentStatus = 'all';
        }
        if (! in_array($orderStatus, $allowedStatuses, true)) {
            $orderStatus = 'all';
        }

        $sellQuery = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->where('order_status', '!=', 'cancelled');

        $incomeQuery = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->where('payment_status', 'paid');

        $ordersQuery = Order::query()
            ->with('items')
            ->whereBetween('created_at', [$from, $to]);

        if ($paymentStatus !== 'all') {
            $ordersQuery->where('payment_status', $paymentStatus);
        }

        if ($orderStatus !== 'all') {
            $ordersQuery->where('order_status', $orderStatus);
        }

        $stats = [
            'sell' => (clone $sellQuery)->count(),
            'income' => (float) (clone $incomeQuery)->sum('total'),
            'profit' => OrderItem::paidProfit($from->toDateString(), $to->toDateString()),
            'paid_orders' => (clone $incomeQuery)->count(),
        ];

        $orders = $paginate
            ? $ordersQuery->latest()->paginate(20)->withQueryString()
            : $ordersQuery->latest()->limit(2000)->get();

        return [
            'periods' => $periods,
            'period' => $period,
            'from' => $from,
            'to' => $to,
            'paymentStatus' => $paymentStatus,
            'orderStatus' => $orderStatus,
            'stats' => $stats,
            'orders' => $orders,
        ];
    }
}
