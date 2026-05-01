@props([
    'title' => '',
    'value' => '',
    'icon' => '',
    'bgColor' => '',
    'textColor' => '',
])

<div {{ $attributes->merge(['class' => 'nb-stat']) }}>
    <div class="flex items-center gap-3">
        @if($icon)
            <div class="nb-stat-icon">
                {!! $icon !!}
            </div>
        @endif
        <p class="nb-stat-label">{{ $title }}</p>
    </div>
    <div class="nb-stat-value">{{ $value }}</div>
</div>
