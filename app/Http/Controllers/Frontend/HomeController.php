<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->with(['category', 'images'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('status', 1)
            ->with(['subCategories' => function($query) {
                $query->where('status', 1);
            }])
            ->get();

        return view('frontend.home', compact('featuredProducts', 'categories'));
    }
}

