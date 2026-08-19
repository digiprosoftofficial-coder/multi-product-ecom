@php
    $isEdit = isset($product) && $product;
    $name = old('name', $isEdit ? $product->name : '');
    $sku = old('sku', $isEdit ? $product->sku : '');
    $price = old('price', $isEdit ? $product->price : '');
    $costPrice = old('cost_price', $isEdit ? $product->cost_price : '');
    $comparePrice = old('compare_price', $isEdit ? $product->compare_price : '');
    $discountPrice = old('discount_price', $isEdit ? $product->discount_price : '');
    $stock = old('stock', $isEdit ? $product->stock : 0);
    $status = (string) old('status', $isEdit ? $product->status : '1');
    $metaTitle = old('meta_title', $isEdit ? $product->meta_title : '');
    $metaDescription = old('meta_description', $isEdit ? $product->meta_description : '');
    $description = old('description', $isEdit ? $product->description : '');
    $seoOpen = $errors->has('meta_title') || $errors->has('meta_description');
@endphp

<div class="row g-4 product-form-layout">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Basic info</h6>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label">Product name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ $name }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku" value="{{ $sku }}"
                               placeholder="Auto if empty">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    @include('admin.products.partials.category-picker')
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Media</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Thumbnail</label>
                        <label class="product-dropzone" for="thumbnail">
                            <input type="file" class="product-file-input @error('thumbnail') is-invalid @enderror"
                                   id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.jfif,.png,.gif,.webp">
                            <img id="thumbnailPreview"
                                 class="product-dropzone-image {{ $isEdit && $product->thumbnail ? '' : 'd-none' }}"
                                 src="{{ $isEdit && $product->thumbnail ? asset('uploads/products/thumbnails/' . $product->thumbnail) : '' }}"
                                 alt="Thumbnail preview">
                            <span id="thumbnailEmpty" class="product-dropzone-empty {{ $isEdit && $product->thumbnail ? 'd-none' : '' }}">
                                <i class="fas fa-image mb-2"></i>
                                Cover image
                            </span>
                        </label>
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Gallery</label>
                        <label class="product-dropzone product-dropzone-wide" for="images">
                            <input type="file" class="product-file-input @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                   id="images" name="images[]" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.jfif,.png,.gif,.webp" multiple>
                            <span class="product-dropzone-empty">
                                <i class="fas fa-images mb-2"></i>
                                Drop or click to add images
                            </span>
                        </label>
                        <div class="form-text">JPG, PNG, GIF or WEBP. Up to 10MB each. You can select several at once.</div>
                        @error('images')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @foreach($errors->getMessages() as $field => $messages)
                            @if(str_starts_with($field, 'images.'))
                                @foreach($messages as $message)
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @endforeach
                            @endif
                        @endforeach
                        <div id="galleryPreview" class="product-gallery-preview mt-2"></div>
                        @if($isEdit && $product->images->count())
                            <div class="row g-2 mt-2">
                                @foreach($product->images as $image)
                                    <div class="col-4 col-md-3">
                                        <div class="product-gallery-item">
                                            <img src="{{ asset('uploads/products/thumbnails/' . $image->filename) }}" alt="">
                                            <button type="submit"
                                                    form="delete-image-form-{{ $image->id }}"
                                                    class="btn btn-sm btn-danger product-gallery-remove"
                                                    onclick="return confirm('Delete this image?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Description</h6>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="8">{{ $description }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @include('admin.products.partials.rich-editor')
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0">Search listing</h6>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#seoFields">
                    {{ $seoOpen ? 'Hide' : 'Edit' }}
                </button>
            </div>
            <div class="collapse {{ $seoOpen ? 'show' : '' }}" id="seoFields">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                               id="meta_title" name="meta_title" value="{{ $metaTitle }}">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="meta_description" class="form-label">Meta description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                  id="meta_description" name="meta_description" rows="2">{{ $metaDescription }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="product-form-sidebar">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Pricing</h6>
                    <div class="mb-3">
                        <label for="cost_price" class="form-label">Purchase price</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ currency_symbol() }}</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('cost_price') is-invalid @enderror"
                                   id="cost_price" name="cost_price" value="{{ $costPrice }}">
                        </div>
                        <div class="form-text">What you paid per unit. Used for dashboard profit.</div>
                        @error('cost_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Selling price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ currency_symbol() }}</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ $price }}" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    @if(compare_price_enabled())
                    <div class="mb-3">
                        <label for="compare_price" class="form-label">Compare price (MRP)</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ currency_symbol() }}</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('compare_price') is-invalid @enderror"
                                   id="compare_price" name="compare_price" value="{{ $comparePrice }}">
                        </div>
                        <div class="form-text">Optional. Should be higher than selling price.</div>
                        @error('compare_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="discount_percentage" class="form-label">Discount %</label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('discount_percentage') is-invalid @enderror"
                                   id="discount_percentage" name="discount_percentage"
                                   value="{{ old('discount_percentage') }}" placeholder="0">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('discount_percentage')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" id="discount_price" name="discount_price" value="{{ $discountPrice }}">
                    <div class="product-price-summary" id="priceSummary">
                        <div class="text-muted small">Customer pays</div>
                        <div class="fs-4 fw-semibold" id="customerPays">{{ money(0) }}</div>
                        <div class="small text-success" id="saveAmount" hidden></div>
                        <div class="small mt-1" id="estimatedProfit" hidden></div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Inventory</h6>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror"
                               id="stock" name="stock" value="{{ $stock }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="status" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="status"
                               name="status" value="1" {{ $status === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                    <hr>
                    <h6 class="fw-semibold mb-3">Homepage</h6>
                    <p class="text-muted small">Tick where this product should appear.</p>
                    @php
                        $homeFlags = [
                            'is_best_selling' => 'Best selling',
                            'is_featured' => 'Featured',
                            'is_popular' => 'Most popular',
                            'is_new_arrival' => 'Just arrived',
                        ];
                    @endphp
                    @foreach($homeFlags as $flag => $label)
                        <div class="form-check">
                            <input type="hidden" name="{{ $flag }}" value="0">
                            <input class="form-check-input" type="checkbox" id="{{ $flag }}" name="{{ $flag }}" value="1"
                                   {{ (string) old($flag, $isEdit && $product->{$flag} ? '1' : '0') === '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $flag }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-sticky-bar">
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" id="productCancel">Cancel</a>
        <button type="submit" class="btn btn-primary">
            {{ $isEdit ? 'Update product' : 'Create product' }}
        </button>
    </div>
</div>
