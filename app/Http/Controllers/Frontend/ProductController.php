<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 1)->whereNull('parent_id')->orderBy('name')->get();
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
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->with(['images', 'category'])
            ->take(4)
            ->get();

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.product") ? "frontend.{$theme}.product"
            : (\Illuminate\Support\Facades\View::exists("frontend.{$theme}.products.show") ? "frontend.{$theme}.products.show" : 'frontend.products.show');

        return view($view, compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        if ($category->status != 1) {
            abort(404);
        }

        $products = Product::whereIn('category_id', $category->subtreeIds())
            ->where('status', 1)
            ->with('images')
            ->paginate(12)
            ->withQueryString();

        $children = $category->children()->where('status', 1)->orderBy('name')->get();

        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.products.category") ? "frontend.{$theme}.products.category" : 'frontend.products.category';

        return view($view, compact('category', 'products', 'children'));
    }
}
