<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'subCategory');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $totalProducts = Product::count();

        return view('admin.products.index', compact('products', 'categories', 'totalProducts'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)
            ->orderBy('name')
            ->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:subcategories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // Calculate discount_price from discount_percentage if provided (base on price)
        $discountPercentage = $request->input('discount_percentage');
        if (!empty($discountPercentage) && $discountPercentage > 0) {
            $basePrice = $request->price; // user requested: base on price only
            if ($basePrice > 0) {
                $discountAmount = ($basePrice * $discountPercentage) / 100;
                $validated['discount_price'] = round($basePrice - $discountAmount, 2);
            }
        } else {
            // If discount_percentage is empty or 0, use the submitted discount_price or null
            if (empty($request->discount_price)) {
                $validated['discount_price'] = null;
            }
        }

        // Remove discount_percentage from validated as it's not a database field
        unset($validated['discount_percentage']);

        $validated['slug'] = Str::slug($validated['name']);

        if (empty($validated['sku'])) {
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(8));
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = time() . '_thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();
            
            $this->ensureDirectoriesExist('products');
            
            $manager = new ImageManager(new Driver());
            $img = $manager->read($thumbnail->getRealPath());
            $img->scale(width: 300, height: 300);
            Storage::disk('public')->put("uploads/products/thumbnails/{$filename}", $img->encode());
            
            $validated['thumbnail'] = $filename;
        }

        $product = Product::create($validated);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . $index . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                $this->processProductImage($image, $filename);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'filename' => $filename,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'subCategory',
            'childCategory',
            'images' => function ($q) {
                $q->orderBy('sort_order');
            },
        ]);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:subcategories,id',
            'child_category_id' => 'nullable|exists:child_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // Calculate discount_price from discount_percentage if provided (base on price)
        $discountPercentage = $request->input('discount_percentage');
        if (!empty($discountPercentage) && $discountPercentage > 0) {
            $basePrice = $request->price; // base on price only
            if ($basePrice > 0) {
                $discountAmount = ($basePrice * $discountPercentage) / 100;
                $validated['discount_price'] = round($basePrice - $discountAmount, 2);
            }
        } else {
            if (empty($request->discount_price)) {
                $validated['discount_price'] = null;
            }
        }

        // Remove discount_percentage from validated as it's not a database field
        unset($validated['discount_percentage']);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure upload directories exist (mirrors store logic)
        $this->ensureDirectoriesExist('products');

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete("uploads/products/thumbnails/{$product->thumbnail}");
            }

            $thumbnail = $request->file('thumbnail');
            $filename = time() . '_thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();

            $manager = new ImageManager(new Driver());
            $img = $manager->read($thumbnail->getRealPath());
            $img->scale(width: 300, height: 300);
            Storage::disk('public')->put("uploads/products/thumbnails/{$filename}", $img->encode());

            $validated['thumbnail'] = $filename;
        }

        $product->update($validated);

        // Handle new images
        if ($request->hasFile('images')) {
            $maxSortOrder = $product->images()->max('sort_order') ?? -1;

            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . ($maxSortOrder + $index + 1) . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                $this->processProductImage($image, $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'filename' => $filename,
                    'is_primary' => false,
                    'sort_order' => $maxSortOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    
    

    public function destroy(Request $request, Product $product)
    {
        // Delete images
        foreach ($product->images as $image) {
            $this->deleteProductImage($image->filename);
        }

        if ($product->thumbnail) {
            Storage::disk('public')->delete("uploads/products/thumbnails/{$product->thumbnail}");
        }

        $product->delete();

        $redirect = $request->input('redirect');

        return $redirect
            ? redirect($redirect)->with('success', 'Product deleted successfully.')
            : redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function deleteImage(ProductImage $productImage)
    {
        $this->deleteProductImage($productImage->filename);
        $productImage->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    private function ensureDirectoriesExist(string $type): void
    {
        $paths = [
            "uploads/{$type}",
            "uploads/{$type}/thumbnails",
            "uploads/{$type}/medium",
        ];

        foreach ($paths as $path) {
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }
        }
    }

    private function processProductImage($image, string $filename): void
    {
        $this->ensureDirectoriesExist('products');
        
        $manager = new ImageManager(new Driver());
        
        // Thumbnail (300px)
        $img = $manager->read($image->getRealPath());
        $img->scale(width: 300, height: 300);
        Storage::disk('public')->put("uploads/products/thumbnails/{$filename}", $img->encode());

        // Medium (600px)
        $img = $manager->read($image->getRealPath());
        $img->scale(width: 600, height: 600);
        Storage::disk('public')->put("uploads/products/medium/{$filename}", $img->encode());

        // Large (1200px)
        $img = $manager->read($image->getRealPath());
        $img->scale(width: 1200, height: 1200);
        Storage::disk('public')->put("uploads/products/{$filename}", $img->encode());
    }

    private function deleteProductImage(string $filename): void
    {
        Storage::disk('public')->delete([
            "uploads/products/{$filename}",
            "uploads/products/thumbnails/{$filename}",
            "uploads/products/medium/{$filename}",
        ]);
    }
}

