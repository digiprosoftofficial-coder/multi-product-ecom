<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'filename',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        return upload_url('uploads/products/' . $this->filename) ?? asset('images/product-placeholder.svg');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return upload_url('uploads/products/thumbnails/' . $this->filename) ?? asset('images/product-placeholder.svg');
    }

    public function getMediumUrlAttribute(): string
    {
        return upload_url('uploads/products/medium/' . $this->filename) ?? asset('images/product-placeholder.svg');
    }
}

