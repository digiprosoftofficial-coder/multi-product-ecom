<div class="modal fade" id="createSubcategoryModal" tabindex="-1" aria-labelledby="createSubcategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="form_type" value="create_subcategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubcategoryModalLabel">Add Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.subcategories.partials.form-fields', [
                        'subcategory' => null,
                        'categories' => $categories,
                        'defaultCategoryId' => $defaultCategoryId ?? null,
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>
