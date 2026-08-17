<div class="modal fade" id="editChildCategoryModal{{ $childcategory->id }}" tabindex="-1" aria-labelledby="editChildCategoryModalLabel{{ $childcategory->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.childcategories.update', $childcategory) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="edit_childcategory">
                <input type="hidden" name="edit_childcategory_id" value="{{ $childcategory->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="editChildCategoryModalLabel{{ $childcategory->id }}">Edit Child Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.childcategories.partials.form-fields', [
                        'childcategory' => $childcategory,
                        'subCategories' => $subCategories,
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Child Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
