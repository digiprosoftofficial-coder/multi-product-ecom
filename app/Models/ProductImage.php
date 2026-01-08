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
        return asset('uploads/products/' . $this->filename);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return asset('uploads/products/thumbnails/' . $this->filename);
    }

    public function getMediumUrlAttribute(): string
    {
        return asset('uploads/products/medium/' . $this->filename);
    }
}

