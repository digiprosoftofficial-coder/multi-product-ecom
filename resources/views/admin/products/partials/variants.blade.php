@php
    $hasVariants = (string) old('has_variants', $isEdit && $product->has_variants ? '1' : '0') === '1';
    $option1Name = old('option1_name', $isEdit ? ($product->option1_name ?: 'Size') : 'Size');
    $option2Name = old('option2_name', $isEdit ? ($product->option2_name ?: 'Color') : 'Color');
    $option1Values = old('option1_values', $isEdit && $product->has_variants
        ? implode(', ', $product->option1Values())
        : 'S, M, L, XL');
    $option2Values = old('option2_values', $isEdit && $product->has_variants
        ? implode(', ', $product->option2Values())
        : '');
    $existingVariantRows = old('variants');
    if (! is_array($existingVariantRows)) {
        $existingVariantRows = $isEdit
            ? $product->variants->map(fn ($variant) => [
                'option1' => $variant->option1,
                'option2' => $variant->option2,
                'stock' => $variant->stock,
            ])->values()->all()
            : [];
    }
@endphp
<div class="card mb-4" id="productVariantsCard">
    <div class="card-body">
        <h6 class="fw-semibold mb-2">Size &amp; color</h6>
        <p class="text-muted small mb-3">Optional. Use this for shirts, pants, or anything sold in sizes and colors. Simple products can keep a single stock number.</p>
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="has_variants" value="0">
            <input class="form-check-input" type="checkbox" role="switch" id="has_variants"
                   name="has_variants" value="1" {{ $hasVariants ? 'checked' : '' }}>
            <label class="form-check-label" for="has_variants">This product has sizes or colors</label>
        </div>
        <div id="variantsFields" class="{{ $hasVariants ? '' : 'd-none' }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="option1_name" class="form-label">First option</label>
                    <input type="text" class="form-control @error('option1_name') is-invalid @enderror"
                           id="option1_name" name="option1_name" value="{{ $option1Name }}" placeholder="Size">
                    @error('option1_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="option1_values" class="form-label">Values</label>
                    <input type="text" class="form-control @error('option1_values') is-invalid @enderror"
                           id="option1_values" name="option1_values" value="{{ $option1Values }}"
                           placeholder="S, M, L, XL">
                    <div class="form-text">Comma separated. Example: S, M, L, XL</div>
                    @error('option1_values')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="option2_name" class="form-label">Second option</label>
                    <input type="text" class="form-control @error('option2_name') is-invalid @enderror"
                           id="option2_name" name="option2_name" value="{{ $option2Name }}" placeholder="Color">
                    @error('option2_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="option2_values" class="form-label">Values</label>
                    <input type="text" class="form-control @error('option2_values') is-invalid @enderror"
                           id="option2_values" name="option2_values" value="{{ $option2Values }}"
                           placeholder="Black, White, Navy">
                    <div class="form-text">Optional. Leave empty for size only.</div>
                    @error('option2_values')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th id="variantCol1">Size</th>
                            <th id="variantCol2">Color</th>
                            <th style="width: 8rem;">Stock</th>
                        </tr>
                    </thead>
                    <tbody id="variantGrid"></tbody>
                </table>
            </div>
            <div class="form-text mt-2">Each row is one combination. Price stays the same for all options.</div>
        </div>
        <script type="application/json" id="variantStockData">@json($existingVariantRows)</script>
    </div>
</div>
