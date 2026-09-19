<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'option1',
        'option2',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function label(?string $option1Name = null, ?string $option2Name = null): string
    {
        $option1Name = $option1Name ?: ($this->product?->option1_name ?: 'Size');
        $option2Name = $option2Name ?: ($this->product?->option2_name ?: 'Color');
        $parts = [];

        if ($this->option1 !== '') {
            $parts[] = $option1Name.': '.$this->option1;
        }
        if ($this->option2 !== '') {
            $parts[] = $option2Name.': '.$this->option2;
        }

        return implode(', ', $parts);
    }
}
