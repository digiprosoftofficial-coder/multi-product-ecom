@php
    $isActive = $currentCategory && (int) $currentCategory->id === (int) $category->id;
    $query = array_filter([
        'category' => $category,
        'search' => $search ?? null,
        'sort' => (($sort ?? 'latest') !== 'latest') ? $sort : null,
    ]);
    $children = $category->children ?? collect();
    $hasChildren = $children->isNotEmpty();
    $isOpen = false;
    if ($currentCategory && $hasChildren) {
        $inBranch = function ($node) use (&$inBranch, $currentCategory): bool {
            if ((int) $node->id === (int) $currentCategory->id) {
                return true;
            }
            foreach ($node->children ?? [] as $child) {
                if ($inBranch($child)) {
                    return true;
                }
            }

            return false;
        };
        $isOpen = $inBranch($category);
    }
@endphp
<li>
    <div class="shop-cat-row">
        <a href="{{ route('products.category', $query) }}"
           class="shop-cat-link {{ $isActive ? 'is-active' : '' }}">
            {{ $category->name }}
        </a>
        @if($hasChildren)
            <button type="button"
                    class="shop-cat-toggle"
                    aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                    aria-label="{{ $isOpen ? 'Collapse' : 'Expand' }} {{ $category->name }}">
                <i class="fa-solid fa-chevron-down"></i>
            </button>
        @endif
    </div>
    @if($hasChildren)
        <ul class="shop-cat-list shop-cat-children list-unstyled{{ $isOpen ? ' is-open' : '' }}">
            @foreach($children as $child)
                @include('frontend.products.partials.category-tree', [
                    'category' => $child,
                    'currentCategory' => $currentCategory,
                    'search' => $search,
                    'sort' => $sort,
                ])
            @endforeach
        </ul>
    @endif
</li>
