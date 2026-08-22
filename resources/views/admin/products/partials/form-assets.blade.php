@once
@push('styles')
<style>
    .product-form-sidebar {
        position: sticky;
        top: 1rem;
    }
    .product-dropzone {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 160px;
        border: 1px dashed #cbd5e1;
        border-radius: .75rem;
        background: #f8fafc;
        cursor: pointer;
        overflow: hidden;
        text-align: center;
        color: #64748b;
        margin: 0;
    }
    .product-file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        overflow: hidden;
    }
    .product-dropzone-wide {
        min-height: 160px;
    }
    .product-dropzone:hover,
    .product-dropzone.is-dragover {
        border-color: #16a34a;
        background: #ecfdf5;
    }
    .product-dropzone-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .product-dropzone-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        font-size: .875rem;
    }
    .product-gallery-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(88px, 1fr));
        gap: .5rem;
    }
    .product-gallery-item {
        position: relative;
        border-radius: .5rem;
        overflow: hidden;
        aspect-ratio: 1;
        background: #f1f5f9;
    }
    .product-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-gallery-remove {
        position: absolute;
        top: .25rem;
        right: .25rem;
        width: 1.6rem;
        height: 1.6rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .product-price-summary {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .75rem;
        padding: .85rem 1rem;
    }
    .product-sticky-bar {
        position: sticky;
        bottom: 0;
        z-index: 20;
        margin-top: 1.5rem;
        padding: .85rem 1rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: .75rem .75rem 0 0;
        box-shadow: 0 -8px 20px rgba(15, 23, 42, .06);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('productForm');
    var priceInput = document.getElementById('price');
    var costPriceInput = document.getElementById('cost_price');
    var comparePriceInput = document.getElementById('compare_price');
    var estimatedProfit = document.getElementById('estimatedProfit');
    var discountPercentageInput = document.getElementById('discount_percentage');
    var discountPriceHidden = document.getElementById('discount_price');
    var customerPays = document.getElementById('customerPays');
    var saveAmount = document.getElementById('saveAmount');
    var nameInput = document.getElementById('name');
    var metaTitle = document.getElementById('meta_title');
    var thumbnailInput = document.getElementById('thumbnail');
    var thumbnailPreview = document.getElementById('thumbnailPreview');
    var thumbnailEmpty = document.getElementById('thumbnailEmpty');
    var galleryInput = document.getElementById('images');
    var galleryPreview = document.getElementById('galleryPreview');
    var cancelLink = document.getElementById('productCancel');
    var dirty = false;
    var metaTouched = metaTitle && metaTitle.value.trim() !== '';

    function money(value) {
        return @json(currency_symbol()) + (Number(value) || 0).toFixed(2);
    }

    function updatePriceSummary() {
        var price = parseFloat(priceInput && priceInput.value) || 0;
        var percent = parseFloat(discountPercentageInput && discountPercentageInput.value) || 0;
        var finalPrice = price;
        if (price > 0 && percent > 0 && percent <= 100) {
            finalPrice = price - (price * percent) / 100;
        }
        if (discountPriceHidden) {
            discountPriceHidden.value = (percent > 0 && price > 0) ? finalPrice.toFixed(2) : '';
        }
        if (customerPays) {
            customerPays.textContent = money(finalPrice);
        }
        if (saveAmount) {
            var saved = price - finalPrice;
            if (saved > 0) {
                saveAmount.hidden = false;
                saveAmount.textContent = 'Save ' + money(saved) + (percent ? ' (' + percent + '% off)' : '');
            } else {
                saveAmount.hidden = true;
                saveAmount.textContent = '';
            }
        }
        if (estimatedProfit) {
            var cost = parseFloat(costPriceInput && costPriceInput.value) || 0;
            if (cost > 0) {
                estimatedProfit.hidden = false;
                estimatedProfit.textContent = 'Est. profit per unit: ' + money(finalPrice - cost);
                estimatedProfit.className = 'small mt-1 ' + (finalPrice >= cost ? 'text-success' : 'text-danger');
            } else {
                estimatedProfit.hidden = true;
                estimatedProfit.textContent = '';
            }
        }
    }

    function markDirty() {
        dirty = true;
    }

    if (priceInput) priceInput.addEventListener('input', updatePriceSummary);
    if (costPriceInput) costPriceInput.addEventListener('input', updatePriceSummary);
    if (comparePriceInput) comparePriceInput.addEventListener('input', updatePriceSummary);
    if (discountPercentageInput) discountPercentageInput.addEventListener('input', updatePriceSummary);
    if (discountPercentageInput && !discountPercentageInput.value && priceInput && discountPriceHidden) {
        var existingPrice = parseFloat(priceInput.value) || 0;
        var existingDiscount = parseFloat(discountPriceHidden.value) || 0;
        if (existingPrice > 0 && existingDiscount > 0 && existingPrice > existingDiscount) {
            discountPercentageInput.value = (((existingPrice - existingDiscount) / existingPrice) * 100).toFixed(2);
        }
    }
    updatePriceSummary();

    if (nameInput && metaTitle) {
        metaTitle.addEventListener('input', function () {
            metaTouched = true;
        });
        nameInput.addEventListener('input', function () {
            if (!metaTouched) {
                metaTitle.value = nameInput.value;
            }
        });
    }

    if (thumbnailInput && thumbnailPreview) {
        var thumbZone = thumbnailInput.closest('.product-dropzone');
        thumbnailInput.addEventListener('change', function () {
            var file = thumbnailInput.files && thumbnailInput.files[0];
            if (!file) return;
            thumbnailPreview.src = URL.createObjectURL(file);
            thumbnailPreview.classList.remove('d-none');
            if (thumbnailEmpty) thumbnailEmpty.classList.add('d-none');
        });
        if (thumbZone) {
            thumbZone.addEventListener('dragover', function (event) {
                event.preventDefault();
                thumbZone.classList.add('is-dragover');
            });
            thumbZone.addEventListener('dragleave', function () {
                thumbZone.classList.remove('is-dragover');
            });
            thumbZone.addEventListener('drop', function (event) {
                event.preventDefault();
                thumbZone.classList.remove('is-dragover');
                if (event.dataTransfer.files.length) {
                    thumbnailInput.files = event.dataTransfer.files;
                    thumbnailInput.dispatchEvent(new Event('change'));
                }
            });
        }
    }

    var galleryBag = new DataTransfer();
    var mergingGallery = false;

    function renderGallery(files) {
        if (!galleryPreview) return;
        galleryPreview.innerHTML = '';
        Array.prototype.forEach.call(files, function (file, index) {
            var wrap = document.createElement('div');
            wrap.className = 'product-gallery-item';
            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            var remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'btn btn-sm btn-danger product-gallery-remove';
            remove.innerHTML = '<i class="fas fa-times"></i>';
            remove.addEventListener('click', function () {
                var dt = new DataTransfer();
                Array.prototype.forEach.call(galleryBag.files, function (kept, keptIndex) {
                    if (keptIndex !== index) dt.items.add(kept);
                });
                galleryBag = dt;
                syncGalleryInput();
            });
            wrap.appendChild(img);
            wrap.appendChild(remove);
            galleryPreview.appendChild(wrap);
        });
    }

    function syncGalleryInput() {
        mergingGallery = true;
        galleryInput.files = galleryBag.files;
        mergingGallery = false;
        renderGallery(galleryBag.files);
    }

    function addGalleryFiles(fileList) {
        Array.prototype.forEach.call(fileList || [], function (file) {
            if (!file || (file.type && file.type.indexOf('image/') !== 0)) return;
            galleryBag.items.add(file);
        });
        syncGalleryInput();
    }

    if (galleryInput) {
        var galleryZone = galleryInput.closest('.product-dropzone');
        galleryInput.addEventListener('change', function () {
            if (mergingGallery) return;
            addGalleryFiles(galleryInput.files);
        });
        if (galleryZone) {
            ['dragenter', 'dragover'].forEach(function (eventName) {
                galleryZone.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    galleryZone.classList.add('is-dragover');
                });
            });
            ['dragleave', 'drop'].forEach(function (eventName) {
                galleryZone.addEventListener(eventName, function (event) {
                    event.preventDefault();
                    galleryZone.classList.remove('is-dragover');
                });
            });
            galleryZone.addEventListener('drop', function (event) {
                event.preventDefault();
                if (event.dataTransfer.files.length) {
                    addGalleryFiles(event.dataTransfer.files);
                }
            });
        }
    }

    if (form) {
        form.addEventListener('input', markDirty);
        form.addEventListener('change', markDirty);
        form.addEventListener('submit', function () {
            dirty = false;
        });
    }
    if (cancelLink) {
        cancelLink.addEventListener('click', function (event) {
            if (dirty && !confirm('Discard unsaved changes?')) {
                event.preventDefault();
            }
        });
    }
});
</script>
@endpush
@endonce
