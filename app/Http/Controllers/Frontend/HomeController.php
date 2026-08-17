<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Homepage;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $bestSellingProducts = Homepage::products('is_best_selling', (int) Homepage::get('home_best_selling_limit'));
        $featuredProducts = Homepage::products('is_featured', (int) Homepage::get('home_featured_limit'));
        $popularProducts = Homepage::products('is_popular', (int) Homepage::get('home_popular_limit'));
        $newProducts = Homepage::products('is_new_arrival', (int) Homepage::get('home_new_limit'));

        $theme = setting('active_frontend_theme', 'organic-v1');

        return view("frontend.{$theme}.index", compact(
            'categories',
            'bestSellingProducts',
            'featuredProducts',
            'popularProducts',
            'newProducts'
        ));
    }
}
