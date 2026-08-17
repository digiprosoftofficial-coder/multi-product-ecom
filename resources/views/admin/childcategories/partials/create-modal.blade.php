<div class="modal fade" id="createChildCategoryModal" tabindex="-1" aria-labelledby="createChildCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.childcategories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="form_type" value="create_childcategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="createChildCategoryModalLabel">Add Child Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.childcategories.partials.form-fields', [
                        'childcategory' => null,
                        'subCategories' => $subCategories,
                        'defaultSubCategoryId' => $defaultSubCategoryId ?? null,
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Child Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
