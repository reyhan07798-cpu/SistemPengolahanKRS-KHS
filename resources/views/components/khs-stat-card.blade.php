@props([
    'title' => '',
    'value' => '',
    'icon' => '',
    'accent' => '',
    'textColor' => '',
])

<div {{ $attributes->merge(['class' => 'nb-stat']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="flex-1 min-w-0">
            <p class="nb-stat-label">{{ $title }}</p>
            <p class="nb-stat-value mt-3">{{ $value }}</p>
        </div>
        @if($icon)
            <div class="nb-stat-icon">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
