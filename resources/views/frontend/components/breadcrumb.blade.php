<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        @foreach($items as $item)
            @if($item['url'])
                <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>

