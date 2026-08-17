<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with(['category', 'childCategories' => function ($query) {
                $query->orderBy('name');
            }])
            ->withCount('products')
            ->orderBy('name')
            ->paginate(15);
        
        $totalSubCategories = SubCategory::count();
        $categories = Category::orderBy('name')->get();

        return view('admin.subcategories.index', compact('subCategories', 'totalSubCategories', 'categories'));
    }

    public function create()
    {
        return redirect()->route('admin.subcategories.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Generate unique slug
        $validated['slug'] = SubCategory::generateUniqueSlug($validated['name']);

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

        SubCategory::create($validated);

        return redirect()->back()
            ->with('success', 'Subcategory created successfully.');
    }

    public function show(SubCategory $subcategory)
    {
        $subcategory->load([
            'category',
            'childCategories' => function ($q) {
                $q->withCount('products');
            },
            'products' => function ($q) {
                $q->latest();
            },
        ]);

        $categories = Category::orderBy('name')->get();
        $subCategories = SubCategory::with('category')->orderBy('name')->get();

        return view('admin.subcategories.show', compact('subcategory', 'categories', 'subCategories'));
    }

    public function edit(SubCategory $subcategory)
    {
        return redirect()->route('admin.subcategories.show', $subcategory);
    }

    public function update(Request $request, SubCategory $subcategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Generate unique slug (excluding current subcategory)
        $validated['slug'] = SubCategory::generateUniqueSlug($validated['name'], null, $subcategory->id);

        if ($request->hasFile('image')) {
            // Delete old images
            if ($subcategory->image) {
                Storage::disk('public')->delete([
                    "uploads/categories/{$subcategory->image}",
                    "uploads/categories/thumbnails/{$subcategory->image}",
                    "uploads/categories/medium/{$subcategory->image}",
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

        $subcategory->update($validated);

        return redirect()->back()
            ->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(SubCategory $subcategory)
    {
        // Check if subcategory has products
        if ($subcategory->products()->count() > 0) {
            return redirect()->route('admin.subcategories.index')
                ->with('error', 'Cannot delete subcategory with products.');
        }

        // Check if subcategory has child categories
        if ($subcategory->childCategories()->count() > 0) {
            return redirect()->route('admin.subcategories.index')
                ->with('error', 'Cannot delete subcategory with child categories.');
        }

        // Delete image
        if ($subcategory->image) {
            Storage::disk('public')->delete([
                "uploads/categories/{$subcategory->image}",
                "uploads/categories/thumbnails/{$subcategory->image}",
                "uploads/categories/medium/{$subcategory->image}",
            ]);
        }

        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
    }

    public function getChildCategories(SubCategory $subcategory)
    {
        $childCategories = \App\Models\ChildCategory::where('sub_category_id', $subcategory->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
        return response()->json($childCategories);
    }
}

