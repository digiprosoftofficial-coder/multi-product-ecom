@php
    $levels = $categoryPickerLevels ?? [];
    $selectedLeafId = '';
    if (!empty($levels)) {
        $lastLevel = $levels[array_key_last($levels)];
        $lastSelected = $lastLevel['selected'] ?? null;
        $lastOptions = $lastLevel['options'] ?? collect();
        $lastChosen = $lastOptions->first(fn ($option) => (int) $option['id'] === (int) $lastSelected);
        if ($lastChosen && $lastChosen['is_leaf']) {
            $selectedLeafId = (string) $lastSelected;
        }
    }
@endphp
<div class="mb-3" id="categoryPicker" data-children-url="{{ route('admin.categories.children') }}">
    <label class="form-label">Category <span class="text-danger">*</span></label>
    <input type="hidden" name="category_id" id="productCategoryId" value="{{ $selectedLeafId }}">
    <div id="categoryPickerLevels">
        @forelse($levels as $index => $level)
            <div class="category-picker-level mb-2">
                <label class="form-label mb-1">Level {{ $index + 1 }}</label>
                <select class="form-select @error('category_id') {{ $index === array_key_last($levels) ? 'is-invalid' : '' }} @enderror">
                    <option value="">Select category</option>
                    @foreach($level['options'] as $option)
                        <option value="{{ $option['id'] }}"
                                data-leaf="{{ $option['is_leaf'] ? '1' : '0' }}"
                                {{ (int) ($level['selected'] ?? 0) === (int) $option['id'] ? 'selected' : '' }}>
                            {{ $option['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        @empty
            <div class="alert alert-warning mb-0">No categories yet. Create a last-level category first.</div>
        @endforelse
    </div>
    <div id="categoryPickerPath" class="form-text {{ $selectedLeafId ? 'text-success' : 'text-muted' }}">
        @if($selectedLeafId)
            Keep this last-level category, or pick another path.
        @else
            Pick level by level until you reach a last-level category. Products can only sit on the deepest category.
        @endif
    </div>
    @error('category_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('categoryPicker');
    if (!root) return;

    var childrenUrl = root.getAttribute('data-children-url');
    var hidden = document.getElementById('productCategoryId');
    var levelsWrap = document.getElementById('categoryPickerLevels');
    var pathEl = document.getElementById('categoryPickerPath');
    var form = root.closest('form');

    function selectedOption(select) {
        return select && select.selectedOptions.length ? select.selectedOptions[0] : null;
    }

    function isLeaf(select) {
        var option = selectedOption(select);
        return option && option.value && option.getAttribute('data-leaf') === '1';
    }

    function pathNames() {
        var names = [];
        levelsWrap.querySelectorAll('select').forEach(function (select) {
            var option = selectedOption(select);
            if (option && option.value) {
                names.push(option.textContent.trim());
            }
        });
        return names;
    }

    function syncHidden() {
        var selects = levelsWrap.querySelectorAll('select');
        var last = selects[selects.length - 1];
        hidden.value = isLeaf(last) ? last.value : '';

        if (!pathEl) return;
        if (hidden.value) {
            pathEl.textContent = 'Selected: ' + pathNames().join(' > ');
            pathEl.className = 'form-text text-success';
        } else if (pathNames().length) {
            pathEl.textContent = 'Keep picking until a last-level category.';
            pathEl.className = 'form-text text-muted';
        } else {
            pathEl.textContent = 'Pick level by level until you reach a last-level category. Products can only sit on the deepest category.';
            pathEl.className = 'form-text text-muted';
        }
    }

    function removeAfter(select) {
        var level = select.closest('.category-picker-level');
        var next = level ? level.nextElementSibling : null;
        while (next) {
            var after = next.nextElementSibling;
            next.remove();
            next = after;
        }
    }

    function addLevel(options) {
        var levelNumber = levelsWrap.querySelectorAll('.category-picker-level').length + 1;
        var wrap = document.createElement('div');
        wrap.className = 'category-picker-level mb-2';

        var label = document.createElement('label');
        label.className = 'form-label mb-1';
        label.textContent = 'Level ' + levelNumber;

        var select = document.createElement('select');
        select.className = 'form-select';
        select.innerHTML = '<option value="">Select category</option>';
        options.forEach(function (option) {
            var el = document.createElement('option');
            el.value = option.id;
            el.textContent = option.name;
            el.setAttribute('data-leaf', option.is_leaf ? '1' : '0');
            select.appendChild(el);
        });
        select.addEventListener('change', onChange);

        wrap.appendChild(label);
        wrap.appendChild(select);
        levelsWrap.appendChild(wrap);
    }

    function onChange(event) {
        var select = event.target;
        removeAfter(select);
        hidden.value = '';

        if (!select.value) {
            syncHidden();
            return;
        }

        if (isLeaf(select)) {
            syncHidden();
            return;
        }

        fetch(childrenUrl + '?parent_id=' + encodeURIComponent(select.value), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function (response) {
            if (!response.ok) throw new Error('Failed to load categories');
            return response.json();
        }).then(function (options) {
            if (!options.length) {
                var option = selectedOption(select);
                if (option) option.setAttribute('data-leaf', '1');
                syncHidden();
                return;
            }
            addLevel(options);
            syncHidden();
        }).catch(function () {
            syncHidden();
        });
    }

    levelsWrap.querySelectorAll('select').forEach(function (select) {
        select.addEventListener('change', onChange);
    });

    if (form) {
        form.addEventListener('submit', function (event) {
            syncHidden();
            if (!hidden.value) {
                event.preventDefault();
                if (pathEl) {
                    pathEl.textContent = 'Select a last-level category before saving.';
                    pathEl.className = 'form-text text-danger';
                }
            }
        });
    }

    syncHidden();
});
</script>
@endpush
@endonce
