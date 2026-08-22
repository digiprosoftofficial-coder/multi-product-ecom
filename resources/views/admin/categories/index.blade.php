@extends('admin.layouts.master')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Category tree <span class="badge bg-primary">{{ $all->count() }}</span></h5>
        <small class="text-muted">
            Max depth: {{ $maxDepth === 0 ? 'Unlimited' : $maxDepth }}
            (change this in Settings). Products belong only on a last-level (leaf) category. Every level can have an image.
        </small>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" id="categoryExpandAll">Expand all</button>
        <button type="button" class="btn btn-outline-secondary" id="categoryCollapseAll">Collapse all</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="categoryTreeTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Children</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Add child</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        @include('admin.categories.partials.tree-row', [
                            'category' => $category,
                            'depth' => 1,
                            'parentMap' => $parentMap,
                        ])
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.categories.partials.create-modal', ['eligibleParents' => $eligibleParents])

@foreach($all as $category)
    @include('admin.categories.partials.edit-modal', ['category' => $category, 'eligibleParents' => $eligibleParents])
    <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to delete "{{ $category->name }}"?</p>
                    <div class="alert alert-warning mb-0">
                        Delete child categories and products under this category first.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('styles')
<style>
    #categoryTreeTable {
        --tree-header: #334155;
        --tree-hover: #ecfdf5;
        --tree-line: #16a34a;
    }
    #categoryTreeTable thead th {
        background: var(--tree-header);
        color: #fff;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: .02em;
        border: 0;
        padding-top: .75rem;
        padding-bottom: .75rem;
    }
    #categoryTreeTable tbody td {
        border-color: #e2e8f0;
        vertical-align: middle;
    }
    #categoryTreeTable .category-row[data-depth="1"] > td {
        background: #fff;
    }
    #categoryTreeTable .category-row[data-depth="1"] > td:first-child {
        font-weight: 600;
        color: #64748b;
    }
    #categoryTreeTable .category-row[data-depth="2"] > td {
        background: #f8fafc;
    }
    #categoryTreeTable .category-row[data-depth="3"] > td {
        background: #f1f5f9;
    }
    #categoryTreeTable .category-row[data-depth="4"] > td,
    #categoryTreeTable .category-row[data-depth="5"] > td,
    #categoryTreeTable .category-row[data-depth="6"] > td {
        background: #eef2f7;
    }
    #categoryTreeTable .category-row[data-depth]:not([data-depth="1"]) > td:nth-child(2) {
        box-shadow: inset 3px 0 0 var(--tree-line);
    }
    #categoryTreeTable .category-row[data-has-children="1"] {
        cursor: pointer;
    }
    #categoryTreeTable .category-row[data-has-children="1"] .category-actions,
    #categoryTreeTable .category-row[data-has-children="1"] .category-add-child {
        cursor: default;
    }
    #categoryTreeTable .category-row:hover > td {
        background: var(--tree-hover);
    }
    #categoryTreeTable .category-toggle {
        color: #94a3b8;
        line-height: 1;
        width: 1.25rem;
        text-decoration: none;
    }
    #categoryTreeTable .category-toggle:hover {
        color: var(--tree-line);
    }
    #categoryTreeTable .category-toggle[aria-expanded="true"] {
        color: var(--tree-line);
    }
    #categoryTreeTable .category-thumb {
        width: 36px;
        height: 36px;
        object-fit: cover;
        flex-shrink: 0;
        border: 1px solid #e2e8f0;
        background: #e2e8f0;
    }
    #categoryTreeTable .category-actions .btn,
    #categoryTreeTable .category-add-child .btn {
        width: 2rem;
        height: 2rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
    @include('admin.partials.open-form-modal')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var createModal = document.getElementById('createCategoryModal');
            if (createModal) {
                createModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var parentSelect = createModal.querySelector('[name="parent_id"]');
                    if (!parentSelect) return;
                    var parentId = button && button.getAttribute('data-parent-id') ? button.getAttribute('data-parent-id') : '';
                    parentSelect.value = parentId;
                });
            }

            var table = document.getElementById('categoryTreeTable');
            if (!table) return;

            var STORAGE_KEY = 'admin-category-tree-collapsed';
            var rows = Array.prototype.slice.call(table.querySelectorAll('.category-row'));

            function getCollapsed() {
                try {
                    var raw = localStorage.getItem(STORAGE_KEY);
                    if (raw === null) {
                        return null;
                    }
                    return new Set(JSON.parse(raw));
                } catch (e) {
                    return null;
                }
            }

            function saveCollapsed(collapsed) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(collapsed)));
            }

            function defaultCollapsed() {
                var collapsed = new Set();
                rows.forEach(function (row) {
                    if (row.getAttribute('data-has-children') === '1') {
                        collapsed.add(row.getAttribute('data-id'));
                    }
                });
                return collapsed;
            }

            function isVisible(row, collapsed) {
                if (Number(row.getAttribute('data-depth')) === 1) {
                    return true;
                }
                var parentId = row.getAttribute('data-parent-id');
                var guard = 0;
                while (parentId && guard < 50) {
                    if (collapsed.has(parentId)) {
                        return false;
                    }
                    var parent = table.querySelector('.category-row[data-id="' + parentId + '"]');
                    parentId = parent ? parent.getAttribute('data-parent-id') : '';
                    guard++;
                }
                return true;
            }

            function applyState(collapsed) {
                rows.forEach(function (row) {
                    row.hidden = !isVisible(row, collapsed);
                    var toggle = row.querySelector('.category-toggle');
                    if (!toggle) return;
                    var expanded = !collapsed.has(row.getAttribute('data-id'));
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    var icon = toggle.querySelector('i');
                    if (icon) {
                        icon.className = expanded ? 'fas fa-chevron-down' : 'fas fa-chevron-right';
                    }
                });
            }

            var collapsed = getCollapsed();
            if (collapsed === null) {
                collapsed = defaultCollapsed();
                saveCollapsed(collapsed);
            }
            applyState(collapsed);

            table.addEventListener('click', function (event) {
                if (event.target.closest('.category-actions, .category-add-child')) {
                    return;
                }
                var row = event.target.closest('.category-row');
                if (!row || row.getAttribute('data-has-children') !== '1') {
                    return;
                }
                event.preventDefault();
                var id = row.getAttribute('data-id');
                if (collapsed.has(id)) {
                    collapsed.delete(id);
                } else {
                    collapsed.add(id);
                }
                saveCollapsed(collapsed);
                applyState(collapsed);
            });

            var expandAll = document.getElementById('categoryExpandAll');
            var collapseAll = document.getElementById('categoryCollapseAll');
            if (expandAll) {
                expandAll.addEventListener('click', function () {
                    collapsed = new Set();
                    saveCollapsed(collapsed);
                    applyState(collapsed);
                });
            }
            if (collapseAll) {
                collapseAll.addEventListener('click', function () {
                    collapsed = defaultCollapsed();
                    saveCollapsed(collapsed);
                    applyState(collapsed);
                });
            }
        });
    </script>
@endpush
