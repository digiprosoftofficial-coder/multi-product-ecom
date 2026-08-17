@php
    $depth = $depth ?? 1;
    $canAddChild = $category->canAddChild($parentMap, $category->products_count);
    $hasChildren = $category->children->isNotEmpty();
@endphp
<tr class="category-row"
    data-id="{{ $category->id }}"
    data-parent-id="{{ $category->parent_id ?? '' }}"
    data-depth="{{ $depth }}"
    data-has-children="{{ $hasChildren ? '1' : '0' }}">
    <td>{{ $loop->iteration ?? '' }}</td>
    <td>
        <div class="d-flex align-items-center" style="padding-left: {{ ($depth - 1) * 22 }}px;">
            @if($hasChildren)
                <button type="button" class="btn btn-sm btn-link p-0 me-2 category-toggle" aria-expanded="false" aria-label="Toggle {{ $category->name }}">
                    <i class="fas fa-chevron-right"></i>
                </button>
            @else
                <span class="d-inline-block me-2" style="width: 1.15rem;"></span>
            @endif
            <img src="{{ $category->thumbnail_url }}"
                 alt="{{ $category->name }}"
                 class="category-thumb me-2 rounded-circle">
            <div>
                <strong>{{ $category->name }}</strong>
                <div class="text-muted small">Level {{ $depth }}</div>
            </div>
        </div>
    </td>
    <td class="text-muted">{{ $category->children_count }}</td>
    <td>
        <span class="badge bg-light text-dark border">{{ $category->products_count }}</span>
    </td>
    <td>
        <span class="badge {{ $category->status ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
            {{ $category->status ? 'Active' : 'Inactive' }}
        </span>
    </td>
    <td class="category-add-child">
        @if($canAddChild)
            <button type="button" class="btn btn-sm btn-outline-primary add-child-btn" data-bs-toggle="modal" data-bs-target="#createCategoryModal" data-parent-id="{{ $category->id }}" title="Add child">
                <i class="fas fa-plus"></i>
            </button>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td class="category-actions">
        <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-outline-secondary" title="View">
            <i class="fas fa-eye"></i>
        </a>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}" title="Edit">
            <i class="fas fa-edit"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>

@foreach($category->children as $child)
    @include('admin.categories.partials.tree-row', [
        'category' => $child,
        'depth' => $depth + 1,
        'parentMap' => $parentMap,
        'loop' => (object) ['iteration' => ''],
    ])
@endforeach
