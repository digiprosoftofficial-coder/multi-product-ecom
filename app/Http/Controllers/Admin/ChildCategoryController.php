<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ChildCategoryController extends Controller
{
    public function index()
    {
        $childCategories = ChildCategory::with('subCategory.category')
            ->withCount('products')
            ->orderBy('name')
            ->paginate(15);
        
        $totalChildCategories = ChildCategory::count();

        return view('admin.childcategories.index', compact('childCategories', 'totalChildCategories'));
    }

    public function create()
    {
        $subCategories = SubCategory::where('status', 1)
            ->with('category')
            ->orderBy('name')
            ->get();
        
        return view('admin.childcategories.create', compact('subCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sub_category_id' => 'required|exists:subcategories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Generate unique slug
        $validated['slug'] = ChildCategory::generateUniqueSlug($validated['name']);

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

        ChildCategory::create($validated);

        return redirect()->route('admin.childcategories.index')
            ->with('success', 'Child category created successfully.');
    }

    public function show(ChildCategory $childcategory)
    {
        $childcategory->load([
            'subCategory.category',
            'products' => function ($q) {
                $q->latest()->with('images');
            },
        ]);

        return view('admin.childcategories.show', compact('childcategory'));
    }

    public function edit(ChildCategory $childcategory)
    {
        $subcategories = SubCategory::where('status', 1)
            ->with('category')
            ->orderBy('name')
            ->get();
        
        return view('admin.childcategories.edit', compact('childcategory', 'subcategories'));
    }

    public function update(Request $request, ChildCategory $childcategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sub_category_id' => 'required|exists:subcategories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Generate unique slug (excluding current child category)
        $validated['slug'] = ChildCategory::generateUniqueSlug($validated['name'], null, $childcategory->id);

        if ($request->hasFile('image')) {
            // Delete old images
            if ($childcategory->image) {
                Storage::disk('public')->delete([
                    "uploads/categories/{$childcategory->image}",
                    "uploads/categories/thumbnails/{$childcategory->image}",
                    "uploads/categories/medium/{$childcategory->image}",
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

        $childcategory->update($validated);

        return redirect()->route('admin.childcategories.index')
            ->with('success', 'Child category updated successfully.');
    }

    public function destroy(ChildCategory $childcategory)
    {
        // Check if child category has products
        if ($childcategory->products()->count() > 0) {
            return redirect()->route('admin.childcategories.index')
                ->with('error', 'Cannot delete child category with products.');
        }

        // Delete image
        if ($childcategory->image) {
            Storage::disk('public')->delete([
                "uploads/categories/{$childcategory->image}",
                "uploads/categories/thumbnails/{$childcategory->image}",
                "uploads/categories/medium/{$childcategory->image}",
            ]);
        }

        $childcategory->delete();

        return redirect()->route('admin.childcategories.index')
            ->with('success', 'Child category deleted successfully.');
    }
}
