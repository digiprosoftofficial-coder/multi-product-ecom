<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $type = $request->get('type', 'all'); // all|registered|guest

        $list = $this->buildCustomerList();

        $customers = $list
            ->when($type === 'registered', fn (Collection $items) => $items->where('is_registered', true)->values())
            ->when($type === 'guest', fn (Collection $items) => $items->where('is_registered', false)->values())
            ->when($search !== '', function (Collection $items) use ($search) {
                $needle = Str::lower($search);

                return $items->filter(function (array $customer) use ($needle) {
                    return str_contains(Str::lower($customer['name'] ?? ''), $needle)
                        || str_contains(Str::lower($customer['email'] ?? ''), $needle)
                        || str_contains(Str::lower($customer['phone'] ?? ''), $needle);
                })->values();
            });

        $page = max(1, (int) $request->get('page', 1));
        $perPage = 20;
        $total = $customers->count();
        $pageItems = $customers->forPage($page, $perPage)->values();

        $customers = new LengthAwarePaginator(
            $pageItems,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $stats = [
            'total' => $list->count(),
            'registered' => $list->where('is_registered', true)->count(),
            'guest' => $list->where('is_registered', false)->count(),
        ];

        if ($request->ajax()) {
            return view('admin.customers.partials.results', compact('customers'))->render();
        }

        return view('admin.customers.index', compact('customers', 'stats', 'search', 'type'));
    }

    protected function buildCustomerList(): Collection
    {
        static $cache = null;

        if ($cache instanceof Collection) {
            return $cache;
        }

        $orderRows = Order::query()
            ->select([
                DB::raw('LOWER(customer_email) as email_key'),
                DB::raw('MAX(customer_name) as name'),
                DB::raw('MAX(customer_email) as email'),
                DB::raw('MAX(customer_phone) as phone'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('MAX(created_at) as last_order_at'),
                DB::raw('MAX(total) as last_total'),
            ])
            ->whereNotNull('customer_email')
            ->where('customer_email', '!=', '')
            ->groupBy(DB::raw('LOWER(customer_email)'))
            ->get()
            ->keyBy('email_key');

        $users = User::query()
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'))
            ->orderByDesc('created_at')
            ->get();

        $merged = collect();

        foreach ($users as $user) {
            $emailKey = Str::lower($user->email);
            $order = $orderRows->get($emailKey);

            $merged[$emailKey] = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?: ($order->phone ?? null),
                'is_registered' => true,
                'user_id' => $user->id,
                'orders_count' => (int) ($order->orders_count ?? 0),
                'last_order_at' => $order->last_order_at ?? null,
                'registered_at' => $user->created_at,
            ];
        }

        foreach ($orderRows as $emailKey => $order) {
            if ($merged->has($emailKey)) {
                continue;
            }

            $merged[$emailKey] = [
                'name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'is_registered' => false,
                'user_id' => null,
                'orders_count' => (int) $order->orders_count,
                'last_order_at' => $order->last_order_at,
                'registered_at' => null,
            ];
        }

        $cache = $merged
            ->values()
            ->sortByDesc(function (array $customer) {
                return $customer['last_order_at'] ?? $customer['registered_at'] ?? now()->subYears(50);
            })
            ->values();

        return $cache;
    }
}
