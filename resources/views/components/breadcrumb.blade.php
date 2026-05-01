@props([
    'items' => [],
    'home' => '#',
])

@if(count($items) > 0)
    <nav class="nb-breadcrumb" aria-label="breadcrumb">
        <ol class="nb-breadcrumb-list">
            <li class="nb-breadcrumb-item">
                <a href="{{ $home }}" class="nb-breadcrumb-link" aria-label="Beranda">
                    <span class="material-symbols-outlined">home</span>
                </a>
            </li>
            @foreach($items as $item)
                <li class="nb-breadcrumb-separator" aria-hidden="true"></li>
                <li class="nb-breadcrumb-item">
                    @if($loop->last || empty($item['url']))
                        <span class="nb-breadcrumb-page" aria-current="page">{{ $item['label'] }}</span>
                    @else
                        <a href="{{ $item['url'] }}" class="nb-breadcrumb-link">{{ $item['label'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
