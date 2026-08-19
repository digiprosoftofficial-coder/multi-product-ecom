<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'cost_price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Product profit on paid orders: (selling price - purchase price) * qty.
     * Lines without a purchase price are skipped so missing cost does not inflate profit.
     */
    public static function paidProfit(?string $date = null): float
    {
        $query = static::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereNotNull('order_items.cost_price');

        if ($date) {
            $query->whereDate('orders.created_at', $date);
        }

        return (float) $query
            ->selectRaw('COALESCE(SUM((order_items.price - order_items.cost_price) * order_items.quantity), 0) as profit')
            ->value('profit');
    }
}

