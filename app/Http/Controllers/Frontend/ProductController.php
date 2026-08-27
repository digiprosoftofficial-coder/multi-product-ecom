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
        $query = Product::where('status', 1)->with('category', 'images');

        if ($request->filled('category')) {
            $category = Category::find($request->category);
            if ($category) {
                $query->whereIn('category_id', $category->subtreeIds());
            }
        }

        $this->applySearch($query, $request);
        $this->applySort($query, $request);

        $products = $query->paginate(12)->withQueryString();
        $categories = Storefront::shopCategories();
        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.shop") ? "frontend.{$theme}.shop" : 'frontend.products.index';

        return view($view, compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if ($product->status != 1) {
            abort(404);
        }

        $product->load('category', 'images');
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

        $query = Product::whereIn('category_id', $category->subtreeIds())
            ->where('status', 1)
            ->with(['images', 'category']);

        $this->applySearch($query, $request);
        $this->applySort($query, $request);

        $products = $query->paginate(12)->withQueryString();
        $children = $category->children()->where('status', 1)->orderBy('name')->get();
        $categories = Storefront::shopCategories();

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.products.category") ? "frontend.{$theme}.products.category" : 'frontend.products.category';

        return view($view, compact('category', 'products', 'children', 'categories'));
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
}
