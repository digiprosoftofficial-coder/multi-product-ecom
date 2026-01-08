<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ChildCategory extends Model
{
    use HasFactory;

    protected $table = 'child_categories';

    protected $fillable = [
        'name',
        'slug',
        'sub_category_id',
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

        static::creating(function ($childCategory) {
            if (empty($childCategory->slug)) {
                $childCategory->slug = static::generateUniqueSlug($childCategory->name);
            }
        });

        static::updating(function ($childCategory) {
            if ($childCategory->isDirty('name') && empty($childCategory->slug)) {
                $childCategory->slug = static::generateUniqueSlug($childCategory->name, null, $childCategory->id);
            }
        });
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'child_category_id');
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

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
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
