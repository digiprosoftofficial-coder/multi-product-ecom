@php
    $product = $item['product'] ?? null;
    $compact = $compact ?? false;
@endphp
@if($product && $product->hasVariants())
    @php
        $option1Name = $product->option1_name ?: 'Size';
        $option2Name = $product->option2_name ?: 'Color';
        $option1Values = $product->option1Values();
        $option2Values = $product->option2Values();
        $current = $item['variant'] ?? null;
        $current1 = $current->option1 ?? ($option1Values[0] ?? '');
        $current2 = $current->option2 ?? ($option2Values[0] ?? '');
    @endphp
    <form action="{{ route('cart.variant', $product) }}"
          method="POST"
          class="js-change-cart-variant {{ $compact ? 'cart-variant-picker cart-variant-picker--compact' : 'cart-variant-picker' }}"
          data-variants='@json($product->variantsJson())'>
        @csrf
        <input type="hidden" name="from_variant_id" value="{{ $item['variant_id'] ?? '' }}">
        <input type="hidden" name="variant_id" class="js-variant-id" value="{{ $item['variant_id'] ?? '' }}">
        <input type="hidden" name="quantity" value="{{ $item['quantity'] ?? 1 }}">
        @if($option1Values)
            <label class="cart-variant-field">
                <span>{{ $option1Name }}</span>
                <select class="form-select form-select-sm" data-cart-option="1" aria-label="{{ $option1Name }}">
                    @foreach($option1Values as $value)
                        <option value="{{ $value }}" @selected($value === $current1)>{{ $value }}</option>
                    @endforeach
                </select>
            </label>
        @endif
        @if($option2Values)
            <label class="cart-variant-field">
                <span>{{ $option2Name }}</span>
                <select class="form-select form-select-sm" data-cart-option="2" aria-label="{{ $option2Name }}">
                    @foreach($option2Values as $value)
                        <option value="{{ $value }}" @selected($value === $current2)>{{ $value }}</option>
                    @endforeach
                </select>
            </label>
        @endif
    </form>
@elseif(!empty($item['variant_label']))
    <div class="small text-muted">{{ $item['variant_label'] }}</div>
@endif
