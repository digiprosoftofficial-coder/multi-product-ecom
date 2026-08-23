@php
    $variant = $variant ?? 'default';
@endphp

<nav aria-label="breadcrumb" class="page-breadcrumb page-breadcrumb--{{ $variant }}">
    <ol class="page-breadcrumb-list">
        @foreach($items as $index => $item)
            <li class="page-breadcrumb-item @if(empty($item['url'])) is-active @endif">
                @if(! empty($item['url']))
                    <a href="{{ $item['url'] }}">
                        @if($index === 0)
                            <i class="fa-solid fa-house" aria-hidden="true"></i>
                        @endif
                        <span>{{ $item['name'] }}</span>
                    </a>
                @else
                    <span aria-current="page">{{ $item['name'] }}</span>
                @endif
            </li>
            @if(! $loop->last)
                <li class="page-breadcrumb-sep" aria-hidden="true">
                    <i class="fa-solid fa-chevron-right"></i>
                </li>
            @endif
        @endforeach
    </ol>
</nav>

@once
@push('styles')
<style>
    .page-breadcrumb {
        margin-bottom: 1.25rem;
    }
    .page-breadcrumb-list {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .35rem .5rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .page-breadcrumb-item a,
    .page-breadcrumb-item span {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .92rem;
        line-height: 1.4;
    }
    .page-breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
        transition: color .15s ease;
    }
    .page-breadcrumb-item a:hover {
        color: #16a34a;
    }
    .page-breadcrumb-item.is-active span {
        color: #0f172a;
        font-weight: 600;
    }
    .page-breadcrumb-sep {
        color: #cbd5e1;
        font-size: .62rem;
        line-height: 1;
    }
    .page-breadcrumb--modern {
        background: linear-gradient(180deg, #f8fbf9 0%, #f3f7f4 100%);
        border: 1px solid #e2ebe5;
        border-radius: 14px;
        padding: .85rem 1rem;
    }
    .page-breadcrumb--modern .page-breadcrumb-item a {
        color: #475569;
    }
    .page-breadcrumb--modern .page-breadcrumb-item.is-active span {
        color: #14532d;
    }
    @media (max-width: 767px) {
        .page-breadcrumb--modern {
            padding: .75rem .85rem;
        }
        .page-breadcrumb-item a,
        .page-breadcrumb-item span {
            font-size: .86rem;
        }
    }
</style>
@endpush
@endonce
