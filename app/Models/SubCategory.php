<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'subcategories';

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'status',
        'image',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subCategory) {
            if (empty($subCategory->slug)) {
                $subCategory->slug = static::generateUniqueSlug($subCategory->name);
            }
        });

        static::updating(function ($subCategory) {
            if ($subCategory->isDirty('name') && empty($subCategory->slug)) {
                $subCategory->slug = static::generateUniqueSlug($subCategory->name, null, $subCategory->id);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'sub_category_id');
    }

    public function childCategories(): HasMany
    {
        return $this->hasMany(ChildCategory::class, 'sub_category_id');
    }

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        return asset('uploads/categories/' . $this->image);
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public static function generateUniqueSlug(string $name, ?string $baseSlug = null, ?int $excludeId = null): string
    {
        $slug = $baseSlug ?? Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

