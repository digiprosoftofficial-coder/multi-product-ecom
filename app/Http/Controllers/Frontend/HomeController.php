<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Load active categories with their active subcategories for the Organic v1 homepage
        $categories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $bestSellingProducts = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->with(['category', 'images', 'orderItems'])
            ->latest()
            ->take(10)
            ->get();

        $theme = setting('active_frontend_theme', 'organic-v1');

        return view("frontend.{$theme}.index", compact('categories', 'bestSellingProducts'));
    }
}

