<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $all = Category::withCount(['children', 'products'])->orderBy('name')->get();
        $parentMap = $all->pluck('parent_id', 'id')->all();
        $all->each(function (Category $category) use ($all) {
            $category->setRelation('children', $all->where('parent_id', $category->id)->values());
        });
        $categories = $all->whereNull('parent_id')->values();
        $maxDepth = Category::maxDepth();
        $allById = $all->keyBy('id');
        $eligibleParents = $all->filter(function (Category $category) use ($parentMap) {
            return $category->canAddChild($parentMap, $category->products_count);
        })->map(function (Category $category) use ($allById) {
            $category->path_name = $category->pathName($allById);

            return $category;
        });

        return view('admin.categories.index', compact('categories', 'all', 'parentMap', 'maxDepth', 'eligibleParents'));
    }

    public function children(Request $request)
    {
        $parentId = $request->filled('parent_id') ? (int) $request->parent_id : null;

        return response()->json(Category::pickerOptions($parentId));
    }

    public function create()
    {
        return redirect()->route('admin.categories.index');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['slug'] = Category::generateUniqueSlug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children' => fn ($q) => $q->withCount(['children', 'products'])->orderBy('name')]);
        $products = Product::whereIn('category_id', $category->subtreeIds())->with('images')->latest()->paginate(15);
        $all = Category::withCount('products')->orderBy('name')->get();
        $parentMap = $all->pluck('parent_id', 'id')->all();
        $allById = $all->keyBy('id');
        $eligibleParents = $all->filter(fn (Category $item) => $item->canAddChild($parentMap, $item->products_count))
            ->map(function (Category $item) use ($allById) {
                $item->path_name = $item->pathName($allById);

                return $item;
            });
        $maxDepth = Category::maxDepth();

        return view('admin.categories.show', compact('category', 'products', 'eligibleParents', 'maxDepth', 'parentMap'));
    }

    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.show', $category);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validatedData($request, $category);

        if (! empty($validated['parent_id']) && (int) $validated['parent_id'] === (int) $category->id) {
            throw ValidationException::withMessages(['parent_id' => 'A category cannot be its own parent.']);
        }

        if (! empty($validated['parent_id']) && $category->isAncestorOf((int) $validated['parent_id'])) {
            throw ValidationException::withMessages(['parent_id' => 'Cannot move a category under one of its children.']);
        }

        $validated['slug'] = Category::generateUniqueSlug($validated['name'], null, $category->id);

        if ($request->hasFile('image')) {
            $this->deleteImage($category->image);
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        $category->update($validated);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete a category that has child categories.');
        }

        if ($category->products()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete a category that has products. Move or delete the products first.');
        }

        $this->deleteImage($category->image);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function getChildren(Category $category)
    {
        $children = $category->children()->where('status', 1)->orderBy('name')->get(['id', 'name', 'slug']);

        return response()->json($children);
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => image_upload_rules(),
        ]);

        $validated['parent_id'] = $validated['parent_id'] ?: null;

        if ($validated['parent_id']) {
            $parent = Category::withCount('products')->findOrFail($validated['parent_id']);
            if ($category && (int) $parent->id === (int) $category->id) {
                throw ValidationException::withMessages(['parent_id' => 'A category cannot be its own parent.']);
            }
            if (! $parent->canAddChild()) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Cannot add a child here. Either this category already has products, or max depth is reached. Products can only stay on the last level.',
                ]);
            }
        }

        return $validated;
    }

    private function storeImage($image): string
    {
        $filename = time().'_'.Str::random(10).'.'.$image->getClientOriginalExtension();

        foreach (['categories', 'categories/thumbnails', 'categories/medium'] as $path) {
            if (! Storage::disk('public')->exists("uploads/{$path}")) {
                Storage::disk('public')->makeDirectory("uploads/{$path}");
            }
        }

        $manager = new ImageManager(new Driver());

        $img = $manager->read($image->getRealPath());
        $img->scale(width: 300, height: 300);
        Storage::disk('public')->put("uploads/categories/thumbnails/{$filename}", $img->encode());

        $img = $manager->read($image->getRealPath());
        $img->scale(width: 600, height: 600);
        Storage::disk('public')->put("uploads/categories/medium/{$filename}", $img->encode());

        $img = $manager->read($image->getRealPath());
        $img->scale(width: 1200, height: 1200);
        Storage::disk('public')->put("uploads/categories/{$filename}", $img->encode());

        return $filename;
    }

    private function deleteImage(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        Storage::disk('public')->delete([
            "uploads/categories/{$filename}",
            "uploads/categories/thumbnails/{$filename}",
            "uploads/categories/medium/{$filename}",
        ]);
    }
}
