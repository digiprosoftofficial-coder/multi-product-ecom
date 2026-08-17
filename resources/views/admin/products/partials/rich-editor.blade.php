@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof tinymce === 'undefined') return;
    var field = document.getElementById('description');
    if (!field) return;

    tinymce.init({
        selector: '#description',
        license_key: 'gpl',
        menubar: false,
        branding: false,
        promotion: false,
        height: 360,
        plugins: 'lists link table autoresize',
        toolbar: 'blocks | bold italic underline | bullist numlist | link table | undo redo',
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3',
        convert_urls: false,
        default_link_target: '_blank',
        link_default_target: '_blank',
        table_toolbar: 'tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        content_style: 'body { font-family: Figtree, Segoe UI, sans-serif; font-size: 14px; line-height: 1.6; }',
        setup: function (editor) {
            var form = field.closest('form');
            if (!form) return;
            form.addEventListener('submit', function () {
                editor.save();
            });
        }
    });
});
</script>
@endpush
@endonce
