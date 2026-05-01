{{-- KRS Package Card Component --}}
@props([
    'title' => '',
    'value' => '',
    'icon' => '',
    'accent' => '',
    'textColor' => '',
])

<div {{ $attributes->merge(['class' => 'nb-card']) }}>
    <div class="flex items-center justify-between gap-4">
        <div class="min-w-0">
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
