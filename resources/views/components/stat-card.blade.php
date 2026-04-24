@props([
    'title' => '',
    'value' => '',
    'icon' => '',
    'bgColor' => 'bg-gray-100',
    'textColor' => 'text-gray-500'
])

<div {{ $attributes->merge(['class' => 'bg-white p-4 rounded-xl shadow-sm border border-gray-100']) }}>
    <div class="flex items-center gap-3">
        <div class="p-2 {{ $bgColor }} rounded-lg {{ $textColor }}">
            {!! $icon !!}
        </div>
        <div>
            <p class="text-xs font-medium text-gray-500">{{ $title }}</p>
            <p class="text-xl font-bold text-dark">{{ $value }}</p>
        </div>
    </div>
</div>