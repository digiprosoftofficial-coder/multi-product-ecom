<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'status',
        'image',
    ];

    protected $casts = [
        'status' => 'integer',
        'parent_id' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            } else {
                $category->slug = static::generateUniqueSlug($category->slug, $category->slug);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name, null, $category->id);
            } elseif ($category->isDirty('slug')) {
                $category->slug = static::generateUniqueSlug($category->slug, $category->slug, $category->id);
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?string $baseSlug = null, ?int $excludeId = null): string
    {
        $slug = $baseSlug ?? Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public static function maxDepth(): int
    {
        return (int) setting('category_max_depth', 3);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function isLeaf(): bool
    {
        if ($this->relationLoaded('children')) {
            return $this->children->isEmpty();
        }

        return ! $this->children()->exists();
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/category-placeholder.svg');
        }

        return asset('uploads/categories/'.$this->image);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/category-placeholder.svg');
        }

        return asset('uploads/categories/thumbnails/'.$this->image);
    }

    public function subtreeIds(): array
    {
        $ids = [$this->id];
        $children = static::where('parent_id', $this->id)->get(['id', 'parent_id']);
        foreach ($children as $child) {
            $ids = array_merge($ids, $child->subtreeIds());
        }

        return $ids;
    }

    public function calculateDepth(?array $parentMap = null): int
    {
        if (! $this->parent_id) {
            return 1;
        }

        if ($parentMap === null) {
            $parentMap = static::query()->pluck('parent_id', 'id')->all();
        }

        $depth = 1;
        $parentId = $this->parent_id;
        $guard = 0;
        while ($parentId && $guard < 50) {
            $depth++;
            $parentId = $parentMap[$parentId] ?? null;
            $guard++;
        }

        return $depth;
    }

    public function canAddChild(?array $parentMap = null, ?int $directProductCount = null): bool
    {
        $productCount = $directProductCount ?? ($this->products_count ?? $this->products()->count());
        if ($productCount > 0) {
            return false;
        }

        $max = static::maxDepth();
        if ($max === 0) {
            return true;
        }

        return $this->calculateDepth($parentMap) < $max;
    }

    public function pathName(?Collection $allById = null): string
    {
        $allById ??= static::all()->keyBy('id');
        $parts = [$this->name];
        $parentId = $this->parent_id;
        $guard = 0;
        while ($parentId && $allById->has($parentId) && $guard < 50) {
            array_unshift($parts, $allById[$parentId]->name);
            $parentId = $allById[$parentId]->parent_id;
            $guard++;
        }

        return implode(' > ', $parts);
    }

    public function pathIds(): array
    {
        $ids = [];
        $node = $this;
        $guard = 0;
        while ($node && $guard < 50) {
            array_unshift($ids, (int) $node->id);
            $node = $node->parent;
            $guard++;
        }

        return $ids;
    }

    public static function pickerOptions(?int $parentId = null): Collection
    {
        return static::query()
            ->where('status', 1)
            ->when(
                $parentId,
                fn ($query) => $query->where('parent_id', $parentId),
                fn ($query) => $query->whereNull('parent_id')
            )
            ->withCount('children')
            ->orderBy('name')
            ->get()
            ->map(fn (self $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'is_leaf' => $category->children_count === 0,
            ])
            ->values();
    }

    public static function pickerLevels(?int $selectedId = null): array
    {
        $path = [];
        if ($selectedId) {
            $selected = static::find($selectedId);
            $path = $selected ? $selected->pathIds() : [];
        }

        $levels = [];
        $parentId = null;
        $levelIndex = 0;

        do {
            $options = static::pickerOptions($parentId);
            if ($options->isEmpty()) {
                break;
            }

            $selected = $path[$levelIndex] ?? null;
            $levels[] = [
                'selected' => $selected,
                'options' => $options,
            ];

            if (! $selected) {
                break;
            }

            $chosen = $options->first(fn ($option) => (int) $option['id'] === (int) $selected);
            if (! $chosen || $chosen['is_leaf']) {
                break;
            }

            $parentId = (int) $selected;
            $levelIndex++;
        } while ($levelIndex < 20);

        return $levels;
    }

    public static function leafSelectOptions(): Collection
    {
        $idsWithChildren = static::whereNotNull('parent_id')->distinct()->pluck('parent_id');
        $leaves = static::where('status', 1)
            ->whereNotIn('id', $idsWithChildren)
            ->orderBy('name')
            ->get();
        $allById = static::all()->keyBy('id');

        return $leaves->map(function (self $leaf) use ($allById) {
            $leaf->path_name = $leaf->pathName($allById);

            return $leaf;
        })->sortBy('path_name', SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    public static function currentTreeMaxDepth(): int
    {
        $parentMap = static::query()->pluck('parent_id', 'id')->all();
        $max = 1;
        foreach ($parentMap as $id => $parentId) {
            $max = max($max, (new static(['id' => $id, 'parent_id' => $parentId]))->calculateDepth($parentMap));
        }

        return $max;
    }

    public function isAncestorOf(int $categoryId): bool
    {
        return in_array($categoryId, $this->subtreeIds(), true);
    }
}
