
<div class="nb-page-header">
    <div>
        @props([
            'eyebrow' => '',
            'title' => 'Selamat Datang',
            'description' => ''
        ])
        
        <span class="nb-eyebrow">{{ $eyebrow }}</span>
        <h1 class="mt-2">{{ $title }}</h1>
        <p>{{ $description }}</p>
    </div>
    <div class="flex gap-3 flex-wrap">
        @foreach($buttons ?? [] as $button)
            <a href="{{ $button['route'] }}" class="nb-btn {{ $button['variant'] ?? 'nb-btn-primary' }}">
                @if(isset($button['icon']))
                    <span class="material-symbols-outlined" style="font-size:20px;">{{ $button['icon'] }}</span>
                @endif
                {{ $button['label'] }}
            </a>
        @endforeach
    </div>
</div>

