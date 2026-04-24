@props([
    'label' => '',
    'value' => ''
])

<div class="mb-4">
    <p class="text-xs font-medium text-gray-500 mb-1">{{ $label }}</p>
    <p class="text-sm font-semibold text-dark">{{ $value ?? '-' }}</p>
</div>