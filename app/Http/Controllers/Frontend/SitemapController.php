<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            [
                'loc' => route('home'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('products.index'),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('about'),
                'changefreq' => 'monthly',
                'priority' => '0.4',
            ],
            [
                'loc' => route('contact'),
                'changefreq' => 'monthly',
                'priority' => '0.4',
            ],
        ];

        $categories = Category::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['slug', 'updated_at']);

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('products.category', $category),
                'lastmod' => optional($category->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        $products = Product::query()
            ->where('status', 1)
            ->latest('updated_at')
            ->get(['slug', 'updated_at']);

        foreach ($products as $product) {
            $urls[] = [
                'loc' => route('products.show', $product),
                'lastmod' => optional($product->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        return response()
            ->view('frontend.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
