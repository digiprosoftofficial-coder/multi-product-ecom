<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\Storefront;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $theme = setting('active_frontend_theme', 'organic-v1');
        $query = Product::where('status', 1)->with('category', 'images');
        $boundCategory = null;

        if ($request->filled('category')) {
            $boundCategory = Category::find($request->category);
            if ($boundCategory) {
                $query->whereIn('category_id', $boundCategory->subtreeIds());
            }
        }

        $priceBounds = $theme === 'organic-v1' ? $this->priceBounds($boundCategory) : null;

        $this->applySearch($query, $request);
        $this->applyPriceRange($query, $request, $theme);
        $this->applySort($query, $request);

        $products = $query->paginate(12)->withQueryString();
        $categories = Storefront::shopCategories();
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.shop") ? "frontend.{$theme}.shop" : 'frontend.products.index';

        return view($view, compact('products', 'categories', 'priceBounds'));
    }

    public function show(Product $product)
    {
        if ($product->status != 1) {
            abort(404);
        }

        $product->load('category', 'images', 'variants');
        $relatedProducts = $this->relatedProductsFor($product);

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.product") ? "frontend.{$theme}.product"
            : (\Illuminate\Support\Facades\View::exists("frontend.{$theme}.products.show") ? "frontend.{$theme}.products.show" : 'frontend.products.show');

        return view($view, compact('product', 'relatedProducts'));
    }

    /**
     * Same-category products first; fill remaining slots with other active products.
     */
    protected function relatedProductsFor(Product $product, int $limit = 4)
    {
        $excludeIds = [$product->id];

        $related = Product::query()
            ->where('status', 1)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->with(['images', 'category'])
            ->latest()
            ->take($limit)
            ->get();

        if ($related->count() >= $limit) {
            return $related;
        }

        $excludeIds = array_merge($excludeIds, $related->pluck('id')->all());
        $needed = $limit - $related->count();

        $fallback = Product::query()
            ->where('status', 1)
            ->whereNotIn('id', $excludeIds)
            ->with(['images', 'category'])
            ->latest()
            ->take($needed)
            ->get();

        return $related->concat($fallback)->values();
    }

    public function category(Request $request, Category $category)
    {
        if ($category->status != 1) {
            abort(404);
        }

        $theme = setting('active_frontend_theme', 'organic-v1');
        $query = Product::whereIn('category_id', $category->subtreeIds())
            ->where('status', 1)
            ->with(['images', 'category']);

        $priceBounds = $theme === 'organic-v1' ? $this->priceBounds($category) : null;

        $this->applySearch($query, $request);
        $this->applyPriceRange($query, $request, $theme);
        $this->applySort($query, $request);

        $products = $query->paginate(12)->withQueryString();
        $children = $category->children()->where('status', 1)->orderBy('name')->get();
        $categories = Storefront::shopCategories();
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.products.category") ? "frontend.{$theme}.products.category" : 'frontend.products.category';

        return view($view, compact('category', 'products', 'children', 'categories', 'priceBounds'));
    }

    protected function applySearch(Builder $query, Request $request): void
    {
        if (! $request->filled('search')) {
            return;
        }

        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%');
        });
    }

    protected function applySort(Builder $query, Request $request): void
    {
        match ($request->get('sort')) {
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) desc'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };
    }

    protected function applyPriceRange(Builder $query, Request $request, string $theme): void
    {
        if ($theme !== 'organic-v1') {
            return;
        }

        $min = $this->priceFilterValue($request, 'min_price');
        $max = $this->priceFilterValue($request, 'max_price');

        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        $priceSql = 'COALESCE(discount_price, price)';

        if ($min !== null) {
            $query->whereRaw($priceSql.' >= ?', [$min]);
        }

        if ($max !== null) {
            $query->whereRaw($priceSql.' <= ?', [$max]);
        }
    }

    protected function priceFilterValue(Request $request, string $key): ?int
    {
        if (! $request->has($key) || $request->input($key) === '' || $request->input($key) === null) {
            return null;
        }

        return max(0, (int) $request->input($key));
    }

    /**
     * Catalog min/max selling price, before the current price filter is applied.
     *
     * @return array{min: int, max: int}
     */
    protected function priceBounds(?Category $category = null): array
    {
        $query = Product::query()->where('status', 1);

        if ($category) {
            $query->whereIn('category_id', $category->subtreeIds());
        }

        $row = $query
            ->selectRaw('MIN(COALESCE(discount_price, price)) as min_price, MAX(COALESCE(discount_price, price)) as max_price')
            ->first();

        $min = (int) floor((float) ($row->min_price ?? 0));
        $max = (int) ceil((float) ($row->max_price ?? 0));

        if ($max < $min) {
            $max = $min;
        }

        return ['min' => $min, 'max' => $max];
    }
}
