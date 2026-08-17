@php
    $openModalId = null;
    if ($errors->any()) {
        $openModalId = match (old('form_type')) {
            'create_subcategory' => 'createSubcategoryModal',
            'edit_subcategory' => old('edit_subcategory_id') ? 'editSubcategoryModal'.((int) old('edit_subcategory_id')) : null,
            'create_childcategory' => 'createChildCategoryModal',
            'edit_childcategory' => old('edit_childcategory_id') ? 'editChildCategoryModal'.((int) old('edit_childcategory_id')) : null,
            default => null,
        };

        if (!$openModalId && old('_method') === 'PUT' && old('edit_category_id')) {
            $openModalId = 'editCategoryModal'.((int) old('edit_category_id'));
        }

        if (!$openModalId && old('_method') !== 'PUT' && !old('form_type') && !empty($fallbackCreateModalId)) {
            $openModalId = $fallbackCreateModalId;
        }
    }
@endphp
@if($openModalId)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById(@json($openModalId));
        if (modalEl && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    });
</script>
@endif
