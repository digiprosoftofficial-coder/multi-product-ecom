@push('styles')
<style>
    .brand-preview {
        position: relative;
        display: inline-block;
        border: 1px solid #e2ebe5;
        border-radius: 10px;
        padding: 8px;
        background: #fff;
    }
    .brand-preview img { display: block; }
    .brand-preview-remove {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        border: 0;
        border-radius: 50%;
        background: #dc2626;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        line-height: 1;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-remove-brand-image').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var wrap = btn.closest('.brand-preview');
            if (!wrap) return;
            var input = wrap.querySelector('input[type="hidden"]');
            if (input) input.value = '1';
            wrap.classList.add('d-none');
        });
    });
});
</script>
@endpush
