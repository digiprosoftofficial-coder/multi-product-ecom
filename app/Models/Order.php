<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'payment_method',
        'payment_status',
        'order_status',
        'subtotal',
        'tax',
        'vat',
        'total',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'vat' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];

        return $badges[$this->order_status] ?? 'secondary';
    }

    public function getPaymentBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'paid' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
        ];

        return $badges[$this->payment_status] ?? 'secondary';
    }

    public function paymentMethodLabel(): string
    {
        return ucwords(str_replace('_', ' ', $this->payment_method));
    }

    public function isAccessibleToCurrentRequest(): bool
    {
        $user = Auth::user();

        if ($user && (int) $this->user_id === (int) $user->id) {
            return true;
        }

        if ((int) session('placed_order_id') === (int) $this->id) {
            if ($this->user_id && Auth::id() && (int) $this->user_id !== (int) Auth::id()) {
                return false;
            }

            return true;
        }

        return request()->hasValidSignature();
    }

    public function applyStatus(string $newStatus): void
    {
        $oldStatus = $this->order_status;

        if ($oldStatus !== $newStatus) {
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                $this->restoreStock();
            }

            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                $this->reserveStock();
            }

            $this->order_status = $newStatus;
        }

        $this->syncPaymentStatus($newStatus);
        $this->save();
    }

    protected function syncPaymentStatus(string $status): void
    {
        if ($status === 'cancelled') {
            $this->payment_status = in_array($this->payment_status, ['paid', 'refunded'], true)
                ? 'refunded'
                : 'cancelled';

            return;
        }

        if ($status === 'delivered') {
            $this->payment_status = 'paid';

            return;
        }

        if (in_array($this->payment_status, ['cancelled', 'refunded'], true)) {
            $this->payment_status = 'pending';
        }
    }

    protected function restoreStock(): void
    {
        $this->loadMissing('items');

        foreach ($this->items as $item) {
            $product = Product::whereKey($item->product_id)->lockForUpdate()->first();
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }
    }

    protected function reserveStock(): void
    {
        $this->loadMissing('items');

        foreach ($this->items as $item) {
            $product = Product::whereKey($item->product_id)->lockForUpdate()->first();

            if (! $product || $product->stock < $item->quantity) {
                throw ValidationException::withMessages([
                    'order_status' => "Cannot reopen this order. Insufficient stock for {$item->product_name}.",
                ]);
            }

            $product->decrement('stock', $item->quantity);
        }
    }
}

