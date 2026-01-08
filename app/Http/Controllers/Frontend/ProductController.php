<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 1)->with('category', 'subCategory', 'images');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('subcategory')) {
            $query->where('sub_category_id', $request->subcategory);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::where('status', 1)->get();

        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if ($product->status != 1) {
            abort(404);
        }

        $product->load('category', 'subCategory', 'images');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->with(['images', 'category'])
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function category(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->where('status', 1)
            ->with('images')
            ->paginate(12);

        $subCategories = \App\Models\SubCategory::where('category_id', $category->id)
            ->where('status', 1)
            ->get();

        return view('frontend.products.category', compact('category', 'products', 'subCategories'));
    }

    public function subCategory(SubCategory $subCategory)
    {
        $products = Product::where('sub_category_id', $subCategory->id)
            ->where('status', 1)
            ->with('images')
            ->paginate(12);

        return view('frontend.products.subcategory', compact('subCategory', 'products'));
    }
}

