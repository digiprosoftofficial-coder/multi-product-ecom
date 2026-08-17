@if($url)
    <div class="brand-preview mb-2" data-remove-input="{{ $removeName }}">
        <input type="hidden" name="{{ $removeName }}" value="0">
        <img src="{{ $url }}" alt="{{ $alt }}" style="{{ $imgStyle }}">
        <button type="button" class="brand-preview-remove js-remove-brand-image" title="Remove" aria-label="Remove">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif
