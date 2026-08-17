<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'category_id',
        'description',
        'price',
        'compare_price',
        'discount_price',
        'stock',
        'status',
        'thumbnail',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name) . '-' . $product->id;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function getFinalPriceAttribute(): float
    {
        if ($this->discount_price) {
            return $this->discount_price;
        }
        return $this->price;
    }

    public function hasDescription(): bool
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->description))) !== '';
    }

    public function getDescriptionHtmlAttribute(): string
    {
        return sanitize_rich_text($this->description) ?? '';
    }

    public function getDescriptionExcerptAttribute(): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->description)));

        return Str::limit($text, 100);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }
        return asset('uploads/products/thumbnails/' . $this->thumbnail);
    }

  
}

