<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\ProductVariants;
use App\Support\StoreImages;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::leafSelectOptions();
        $totalProducts = Product::count();

        if ($request->ajax()) {
            return response()
                ->view('admin.products.partials.results', compact('products'))
                ->header('X-Products-Total', (string) $products->total())
                ->header('X-Products-All', (string) $totalProducts);
        }

        return view('admin.products.index', compact('products', 'categories', 'totalProducts'));
    }

    public function create()
    {
        $categoryPickerLevels = Category::pickerLevels(old('category_id') ? (int) old('category_id') : null);

        return view('admin.products.create', compact('categoryPickerLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku',
            'category_id' => ['required', 'exists:categories,id', $this->leafCategoryRule()],
            'description' => 'nullable|string|max:20000',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => $request->boolean('has_variants') ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'has_variants' => 'nullable|boolean',
            'option1_name' => 'nullable|string|max:40',
            'option2_name' => 'nullable|string|max:40',
            'option1_values' => $request->boolean('has_variants') ? 'required|string|max:500' : 'nullable|string|max:500',
            'option2_values' => 'nullable|string|max:500',
            'variants' => 'nullable|array|max:48',
            'variants.*.option1' => 'nullable|string|max:40',
            'variants.*.option2' => 'nullable|string|max:40',
            'variants.*.stock' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
            'is_featured' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'is_best_selling' => 'nullable|boolean',
            'thumbnail' => image_upload_rules(),
            'images' => 'nullable|array|max:20',
            'images.*' => image_upload_rules(),
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ], [
            'images.max' => 'You can upload at most 20 gallery images at once.',
            'images.*.max' => 'Each gallery image must be 10MB or smaller.',
        ], [
            'thumbnail' => 'thumbnail',
            'images' => 'gallery',
            'images.*' => 'gallery image',
        ]);

        // Calculate discount_price from discount_percentage if provided (base on price)
        $discountPercentage = $request->input('discount_percentage');
        if (!empty($discountPercentage) && $discountPercentage > 0) {
            $basePrice = $request->price; // user requested: base on price only
            if ($basePrice > 0) {
                $discountAmount = ($basePrice * $discountPercentage) / 100;
                $validated['discount_price'] = taka($basePrice - $discountAmount);
            }
        } else {
            // If discount_percentage is empty or 0, use the submitted discount_price or null
            if (empty($request->discount_price)) {
                $validated['discount_price'] = null;
            }
        }

        // Remove discount_percentage from validated as it's not a database field
        unset(
            $validated['discount_percentage'],
            $validated['images'],
            $validated['has_variants'],
            $validated['option1_name'],
            $validated['option2_name'],
            $validated['option1_values'],
            $validated['option2_values'],
            $validated['variants']
        );

        if ($request->boolean('has_variants')) {
            $validated['stock'] = (int) ($validated['stock'] ?? 0);
        }

        $validated = $this->roundMoneyFields($validated);

        foreach (['is_featured', 'is_popular', 'is_new_arrival', 'is_best_selling'] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        if (array_key_exists('description', $validated)) {
            $validated['description'] = sanitize_rich_text($validated['description'] ?? null);
        }

        if (! compare_price_enabled()) {
            $validated['compare_price'] = null;
        }

        $validated['slug'] = Str::slug($validated['name']);

        if (empty($validated['sku'])) {
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(8));
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            $filename = StoreImages::uniqueName('thumb');
            StoreImages::processListingThumb($request->file('thumbnail'), $filename);
            $validated['thumbnail'] = $filename;
        }

        $product = Product::create($validated);
        $this->syncVariants($request, $product);

        foreach ($this->uploadedGalleryImages($request) as $index => $image) {
            $filename = StoreImages::uniqueName((string) $index);

            StoreImages::processGallery($image, $filename);

            ProductImage::create([
                'product_id' => $product->id,
                'filename' => $filename,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'variants',
            'images' => function ($q) {
                $q->orderBy('sort_order');
            },
        ]);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('images', 'variants');
        $categoryPickerLevels = Category::pickerLevels(
            old('category_id', $product->category_id) ? (int) old('category_id', $product->category_id) : null
        );

        return view('admin.products.edit', compact('product', 'categoryPickerLevels'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'category_id' => ['required', 'exists:categories,id', $this->leafCategoryRule()],
            'description' => 'nullable|string|max:20000',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => $request->boolean('has_variants') ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'has_variants' => 'nullable|boolean',
            'option1_name' => 'nullable|string|max:40',
            'option2_name' => 'nullable|string|max:40',
            'option1_values' => $request->boolean('has_variants') ? 'required|string|max:500' : 'nullable|string|max:500',
            'option2_values' => 'nullable|string|max:500',
            'variants' => 'nullable|array|max:48',
            'variants.*.option1' => 'nullable|string|max:40',
            'variants.*.option2' => 'nullable|string|max:40',
            'variants.*.stock' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
            'is_featured' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'is_best_selling' => 'nullable|boolean',
            'thumbnail' => image_upload_rules(),
            'images' => 'nullable|array|max:20',
            'images.*' => image_upload_rules(),
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ], [
            'images.max' => 'You can upload at most 20 gallery images at once.',
            'images.*.max' => 'Each gallery image must be 10MB or smaller.',
        ], [
            'thumbnail' => 'thumbnail',
            'images' => 'gallery',
            'images.*' => 'gallery image',
        ]);

        // Calculate discount_price from discount_percentage if provided (base on price)
        $discountPercentage = $request->input('discount_percentage');
        if (!empty($discountPercentage) && $discountPercentage > 0) {
            $basePrice = $request->price; // base on price only
            if ($basePrice > 0) {
                $discountAmount = ($basePrice * $discountPercentage) / 100;
                $validated['discount_price'] = taka($basePrice - $discountAmount);
            }
        } else {
            if (empty($request->discount_price)) {
                $validated['discount_price'] = null;
            }
        }

        // Remove discount_percentage from validated as it's not a database field
        unset(
            $validated['discount_percentage'],
            $validated['images'],
            $validated['has_variants'],
            $validated['option1_name'],
            $validated['option2_name'],
            $validated['option1_values'],
            $validated['option2_values'],
            $validated['variants']
        );

        if ($request->boolean('has_variants')) {
            $validated['stock'] = (int) ($validated['stock'] ?? 0);
        }

        $validated = $this->roundMoneyFields($validated);

        foreach (['is_featured', 'is_popular', 'is_new_arrival', 'is_best_selling'] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        if (array_key_exists('description', $validated)) {
            $validated['description'] = sanitize_rich_text($validated['description'] ?? null);
        }

        if (! compare_price_enabled()) {
            unset($validated['compare_price']);
        }

        $validated['slug'] = Str::slug($validated['name']);

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete("uploads/products/thumbnails/{$product->thumbnail}");
            }

            $filename = StoreImages::uniqueName('thumb');
            StoreImages::processListingThumb($request->file('thumbnail'), $filename);
            $validated['thumbnail'] = $filename;
        }

        $product->update($validated);
        $this->syncVariants($request, $product);

        $galleryImages = $this->uploadedGalleryImages($request);
        if ($galleryImages) {
            $maxSortOrder = $product->images()->max('sort_order') ?? -1;

            foreach ($galleryImages as $index => $image) {
                $filename = StoreImages::uniqueName((string) ($maxSortOrder + $index + 1));

                StoreImages::processGallery($image, $filename);

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

    /**
     * @return list<UploadedFile>
     */
    private function uploadedGalleryImages(Request $request): array
    {
        $files = $request->file('images', []);
        if (! is_array($files)) {
            $files = $files ? [$files] : [];
        }

        return array_values(array_filter($files, function ($image) {
            return $image instanceof UploadedFile && $image->isValid();
        }));
    }

    private function roundMoneyFields(array $validated): array
    {
        foreach (['price', 'cost_price', 'compare_price', 'discount_price'] as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] !== null && $validated[$field] !== '') {
                $validated[$field] = taka($validated[$field]);
            }
        }

        return $validated;
    }

    private function syncVariants(Request $request, Product $product): void
    {
        ProductVariants::sync($product, $request->boolean('has_variants'), [
            'option1_name' => $request->input('option1_name'),
            'option2_name' => $request->input('option2_name'),
            'option1_values' => $request->input('option1_values'),
            'option2_values' => $request->input('option2_values'),
            'variants' => $request->input('variants', []),
        ]);
    }

    private function leafCategoryRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) {
            $category = Category::find($value);
            if ($category && ! $category->isLeaf()) {
                $fail('Products can only be assigned to a last-level category. Add children first, then put products on the deepest category.');
            }
        };
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

