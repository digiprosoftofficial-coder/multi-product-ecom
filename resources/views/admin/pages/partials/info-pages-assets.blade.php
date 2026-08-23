@push('styles')
<style>
    .info-pages-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }
    .info-pages-intro {
        max-width: 640px;
    }
    .info-pages-tabs {
        border-bottom: 0;
        gap: .45rem;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: .15rem;
        scrollbar-width: thin;
    }
    .info-pages-tabs .nav-link {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        border: 1px solid #e2ebe5;
        border-radius: 999px;
        color: #475569;
        background: #fff;
        padding: .55rem .95rem;
        font-weight: 600;
        white-space: nowrap;
        transition: all .15s ease;
    }
    .info-pages-tabs .nav-link:hover {
        border-color: #b9d8c4;
        color: #14532d;
        background: #f7fbf8;
    }
    .info-pages-tabs .nav-link.active {
        background: #dcfce7;
        border-color: #86efac;
        color: #14532d;
        box-shadow: inset 0 0 0 1px rgba(22, 163, 74, .12);
    }
    .info-pages-tabs .tab-status {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        flex-shrink: 0;
    }
    .info-pages-tabs .nav-link.active .tab-status.is-published,
    .info-pages-tabs .tab-status.is-published {
        background: #22c55e;
    }
    .info-page-section-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .info-page-section-title {
        font-weight: 700;
        color: #0f172a;
    }
    .info-banner-card,
    .info-content-card {
        background: #fff;
        border: 1px solid #e2ebe5;
        border-radius: 14px;
        padding: 1rem;
    }
    .info-banner-preview {
        position: relative;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #1f3b2c 0%, #2f6b45 55%, #6BB252 100%);
        display: flex;
        align-items: flex-end;
    }
    .info-banner-preview.has-image {
        background-size: cover;
        background-position: center;
    }
    .info-banner-preview-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
    }
    .info-banner-preview-text {
        position: relative;
        z-index: 1;
        color: #fff;
        padding: .85rem 1rem;
        width: 100%;
    }
    .info-banner-preview-label {
        display: block;
        font-size: .68rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        opacity: .75;
        margin-bottom: .25rem;
    }
    .info-banner-preview-text strong {
        display: block;
        font-size: 1rem;
        line-height: 1.25;
    }
    .info-banner-preview-text small {
        display: block;
        margin-top: .2rem;
        opacity: .88;
        font-size: .78rem;
        line-height: 1.35;
    }
    .info-pages-savebar {
        position: sticky;
        bottom: 0;
        z-index: 20;
        margin-top: 1.25rem;
        padding: .9rem 1rem;
        border: 1px solid #e2ebe5;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 -8px 24px rgba(15, 23, 42, 0.06);
    }
    .info-pages-savebar .savebar-meta {
        color: #64748b;
        font-size: .92rem;
    }
    @media (max-width: 991px) {
        .info-pages-tabs {
            flex-wrap: nowrap;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var editorsInitialized = {};

    function initEditor(id) {
        if (editorsInitialized[id] || typeof tinymce === 'undefined') return;
        tinymce.init({
            selector: '#' + id,
            license_key: 'gpl',
            menubar: false,
            branding: false,
            promotion: false,
            height: 420,
            plugins: 'lists link',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link | removeformat',
            setup: function (editor) {
                var form = editor.getElement().closest('form');
                if (!form) return;
                form.addEventListener('submit', function () {
                    editor.save();
                });
            }
        });
        editorsInitialized[id] = true;
    }

    function activateTab(tabKey) {
        var trigger = document.querySelector('[data-bs-target="#tab-' + tabKey + '"]');
        if (!trigger || typeof bootstrap === 'undefined') return;
        bootstrap.Tab.getOrCreateInstance(trigger).show();
    }

    var params = new URLSearchParams(window.location.search);
    var hashTab = window.location.hash.replace('#', '');
    var initialTab = params.get('tab') || hashTab || 'privacy';
    activateTab(initialTab);
    initEditor(initialTab + '_content');

    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (tab) {
        tab.addEventListener('shown.bs.tab', function (e) {
            var target = e.target.getAttribute('data-bs-target');
            if (!target) return;
            var pageKey = target.replace('#tab-', '');
            var activeInput = document.getElementById('activeTabInput');
            if (activeInput) activeInput.value = pageKey;
            initEditor(pageKey + '_content');
            var editor = tinymce.get(pageKey + '_content');
            if (editor) editor.fire('ResizeEditor');
        });
    });

    document.querySelectorAll('.js-banner-preview-input').forEach(function (input) {
        input.addEventListener('input', function () {
            var pageKey = input.dataset.previewTitle || input.dataset.previewSubtitle;
            if (!pageKey) return;
            var pane = document.getElementById('tab-' + pageKey);
            if (!pane) return;
            if (input.dataset.previewTitle) {
                var titleEl = pane.querySelector('.info-banner-preview-text strong');
                if (titleEl) titleEl.textContent = input.value || titleEl.dataset.fallback || titleEl.textContent;
            }
            if (input.dataset.previewSubtitle) {
                var subtitleEl = pane.querySelector('.info-banner-preview-text small');
                if (subtitleEl) subtitleEl.textContent = input.value;
            }
        });
    });

    var form = document.getElementById('infoPagesForm');
    if (form) {
        form.addEventListener('submit', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    }
});
</script>
@endpush
