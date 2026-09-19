@if($product->hasVariants())
    @php
        $option1Name = $product->option1_name ?: 'Size';
        $option2Name = $product->option2_name ?: 'Color';
        $option1Values = $product->option1Values();
        $option2Values = $product->option2Values();
    @endphp
    <div class="product-variant-picker mb-3"
         data-variants='@json($product->variantsJson())'>
        <input type="hidden" name="variant_id" class="js-variant-id" value="">
        @if($option1Values)
            <div class="product-variant-group mb-2">
                <div class="product-variant-label">{{ $option1Name }}</div>
                <div class="product-variant-options">
                    @foreach($option1Values as $value)
                        <button type="button" class="product-variant-btn" data-option="1" data-value="{{ $value }}">{{ $value }}</button>
                    @endforeach
                </div>
            </div>
        @endif
        @if($option2Values)
            <div class="product-variant-group mb-2">
                <div class="product-variant-label">{{ $option2Name }}</div>
                <div class="product-variant-options">
                    @foreach($option2Values as $value)
                        <button type="button" class="product-variant-btn" data-option="2" data-value="{{ $value }}">{{ $value }}</button>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="small text-danger js-variant-stock" hidden></div>
    </div>
    @once
        <style>
            .product-variant-label { font-size: .85rem; font-weight: 600; margin-bottom: .4rem; }
            .product-variant-options { display: flex; flex-wrap: wrap; gap: .4rem; }
            .product-variant-btn {
                min-width: 2.6rem; padding: .35rem .7rem; border: 1px solid #cbd5e1;
                border-radius: .55rem; background: #fff; color: inherit; font-size: .85rem; font-weight: 600;
            }
            .product-variant-btn.is-selected { border-color: #16a34a; background: #ecfdf5; color: #166534; }
            .product-variant-btn.is-oos, .product-variant-btn:disabled {
                opacity: .45; text-decoration: line-through; cursor: not-allowed;
            }
            .gadget-v1 .product-variant-btn { background: #111; border-color: #444; color: #eee; }
            .gadget-v1 .product-variant-btn.is-selected { border-color: #6BB252; background: #16321c; color: #c8f7c0; }
        </style>
        <script>
        (function () {
            function initPickers() {
                document.querySelectorAll('.product-variant-picker').forEach(function (root) {
                var variants = [];
                try { variants = JSON.parse(root.getAttribute('data-variants') || '[]'); } catch (e) { variants = []; }
                var form = root.closest('form');
                var hidden = root.querySelector('.js-variant-id');
                var stockEl = root.querySelector('.js-variant-stock');
                var skuEl = document.querySelector('.js-product-sku');
                var qty = form && form.querySelector('.js-product-qty-input, input[name="quantity"]');
                var has2 = root.querySelectorAll('[data-option="2"]').length > 0;
                var selected1 = null;
                var selected2 = null;

                function findVariant() {
                    return variants.find(function (row) {
                        if (row.option1 !== selected1) return false;
                        if (has2 && row.option2 !== selected2) return false;
                        return true;
                    }) || null;
                }

                function apply() {
                    var row = findVariant();
                    if (hidden) hidden.value = row ? String(row.id) : '';
                    var inStock = !!(row && row.stock > 0);
                    if (stockEl) {
                        var showOos = !!(row && row.stock < 1);
                        stockEl.textContent = showOos ? 'Out of stock' : '';
                        stockEl.hidden = !showOos;
                    }
                    if (skuEl && row && row.sku) skuEl.textContent = row.sku;
                    if (qty) {
                        qty.max = Math.max(1, row ? Number(row.stock) : 1);
                        if (Number(qty.value) > Number(qty.max)) qty.value = qty.max;
                    }
                    if (form) {
                        form.querySelectorAll('button[type="submit"]').forEach(function (btn) {
                            btn.disabled = !inStock;
                        });
                    }
                    root.querySelectorAll('[data-option="2"]').forEach(function (btn) {
                        var match = variants.find(function (row) {
                            return row.option1 === selected1 && row.option2 === btn.dataset.value;
                        });
                        btn.disabled = !match || match.stock < 1;
                        btn.classList.toggle('is-oos', btn.disabled);
                    });
                }

                root.addEventListener('click', function (event) {
                    var btn = event.target.closest('[data-option]');
                    if (!btn || btn.disabled) return;
                    var group = btn.dataset.option;
                    root.querySelectorAll('[data-option="' + group + '"]').forEach(function (el) {
                        el.classList.remove('is-selected');
                    });
                    btn.classList.add('is-selected');
                    if (group === '1') selected1 = btn.dataset.value;
                    else selected2 = btn.dataset.value;
                    apply();
                });

                var first = variants.find(function (row) { return row.stock > 0; }) || variants[0];
                if (first) {
                    selected1 = first.option1;
                    selected2 = first.option2;
                    root.querySelectorAll('[data-option="1"]').forEach(function (btn) {
                        btn.classList.toggle('is-selected', btn.dataset.value === selected1);
                    });
                    root.querySelectorAll('[data-option="2"]').forEach(function (btn) {
                        btn.classList.toggle('is-selected', btn.dataset.value === selected2);
                    });
                    apply();
                }

                if (form) {
                    form.addEventListener('submit', function (event) {
                        if (hidden && !hidden.value) {
                            event.preventDefault();
                            event.stopPropagation();
                            alert('Please choose a size or color.');
                        }
                    }, true);
                }
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPickers);
            } else {
                initPickers();
            }
        })();
        </script>
    @endonce
@endif
