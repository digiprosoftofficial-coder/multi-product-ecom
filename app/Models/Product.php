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
        'cost_price',
        'compare_price',
        'discount_price',
        'stock',
        'status',
        'is_featured',
        'is_popular',
        'is_new_arrival',
        'is_best_selling',
        'thumbnail',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'integer',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_best_selling' => 'boolean',
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

    /**
     * List/compare price used to show savings (compare_price preferred when enabled).
     */
    public function listPriceForDiscount(): ?float
    {
        $final = (float) $this->final_price;

        if (compare_price_enabled() && $this->compare_price && (float) $this->compare_price > $final) {
            return (float) $this->compare_price;
        }

        if ($this->discount_price && (float) $this->price > $final) {
            return (float) $this->price;
        }

        return null;
    }

    public function discountPercent(): ?int
    {
        $list = $this->listPriceForDiscount();
        if (! $list) {
            return null;
        }

        $final = (float) $this->final_price;
        $percent = (int) round((($list - $final) / $list) * 100);

        return $percent > 0 ? $percent : null;
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
        return upload_url('uploads/products/thumbnails/' . $this->thumbnail);
    }

    public function seoTitle(): string
    {
        return filled($this->meta_title) ? $this->meta_title : $this->name;
    }

    public function seoDescription(): string
    {
        if (filled($this->meta_description)) {
            return Str::limit(trim($this->meta_description), 160);
        }

        $excerpt = trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->description)));

        return Str::limit($excerpt !== '' ? $excerpt : $this->name, 160);
    }

    public function seoImageUrls(): array
    {
        $urls = [];
        if ($this->thumbnail_url) {
            $urls[] = $this->thumbnail_url;
        }
        foreach ($this->images as $image) {
            $urls[] = $image->image_url;
        }

        return array_values(array_unique($urls));
    }

    public function seoImageUrl(): ?string
    {
        return $this->seoImageUrls()[0] ?? null;
    }

    public function jsonLd(): array
    {
        $url = route('products.show', $this);
        $images = $this->seoImageUrls();

        $data = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $this->name,
            'description' => $this->seoDescription(),
            'sku' => $this->sku,
            'url' => $url,
            'offers' => [
                '@type' => 'Offer',
                'url' => $url,
                'priceCurrency' => \App\Support\Seo::currencyCode(),
                'price' => number_format((float) $this->final_price, 2, '.', ''),
                'availability' => $this->isInStock()
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
        ];

        if ($images) {
            $data['image'] = count($images) === 1 ? $images[0] : $images;
        }

        if ($this->category) {
            $data['category'] = $this->category->pathName();
        }

        return $data;
    }
}

