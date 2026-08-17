<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['subCategories.childCategories' => function ($query) {
                $query->orderBy('name');
            }])
            ->withCount('products')
            ->orderBy('name')
            ->get();
        $totalCategories = Category::count();
        $totalProducts = Product::count();

        return view('admin.categories.index', compact('categories', 'totalCategories', 'totalProducts'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Generate unique slug
        $validated['slug'] = Category::generateUniqueSlug($validated['name']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            // Create directories
            $paths = [
                'categories',
                'categories/thumbnails',
                'categories/medium',
            ];

            foreach ($paths as $path) {
                if (!Storage::disk('public')->exists("uploads/{$path}")) {
                    Storage::disk('public')->makeDirectory("uploads/{$path}");
                }
            }

            // Resize and save images
            $manager = new ImageManager(new Driver());
            
            // Thumbnail (300px)
            $img = $manager->read($image->getRealPath());
            $img->scale(width: 300, height: 300);
            Storage::disk('public')->put("uploads/categories/thumbnails/{$filename}", $img->encode());

            // Medium (600px)
            $img = $manager->read($image->getRealPath());
            $img->scale(width: 600, height: 600);
            Storage::disk('public')->put("uploads/categories/medium/{$filename}", $img->encode());

            // Large (1200px)
            $img = $manager->read($image->getRealPath());
            $img->scale(width: 1200, height: 1200);
            Storage::disk('public')->put("uploads/categories/{$filename}", $img->encode());

            $validated['image'] = $filename;
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.show', $category);
    }

    public function show(Category $category)
    {
        $category->load([
            'subCategories.childCategories' => function ($q) {
                $q->withCount('products')->orderBy('name');
            },
            'products' => function ($q) {
                $q->latest();
            },
        ]);

        return view('admin.categories.show', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Generate unique slug (excluding current category)
        $validated['slug'] = Category::generateUniqueSlug($validated['name'], null, $category->id);

        if ($request->hasFile('image')) {
            // Delete old images
            if ($category->image) {
                Storage::disk('public')->delete([
                    "uploads/categories/{$category->image}",
                    "uploads/categories/thumbnails/{$category->image}",
                    "uploads/categories/medium/{$category->image}",
                ]);
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            $manager = new ImageManager(new Driver());
            
            // Thumbnail
            $img = $manager->read($image->getRealPath());
            $img->scale(width: 300, height: 300);
            Storage::disk('public')->put("uploads/categories/thumbnails/{$filename}", $img->encode());

            // Medium
            $img = $manager->read($image->getRealPath());
            $img->scale(width: 600, height: 600);
            Storage::disk('public')->put("uploads/categories/medium/{$filename}", $img->encode());

            // Large
            $img = $manager->read($image->getRealPath());
            $img->scale(width: 1200, height: 1200);
            Storage::disk('public')->put("uploads/categories/{$filename}", $img->encode());

            $validated['image'] = $filename;
        }

        $category->update($validated);

        return redirect()->back()
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with products.');
        }

        // Check if category has subcategories
        if ($category->subCategories()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with subcategories.');
        }

        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete([
                "uploads/categories/{$category->image}",
                "uploads/categories/thumbnails/{$category->image}",
                "uploads/categories/medium/{$category->image}",
            ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function getSubcategories(Category $category)
    {
        $subcategories = \App\Models\SubCategory::where('category_id', $category->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
        return response()->json($subcategories);
    }
}


