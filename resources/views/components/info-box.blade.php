@props([
    'label' => '',
    'value' => ''
])

<div class="mb-4">
    <p class="nb-label">{{ $label }}</p>
    <p class="text-base font-bold text-ink">{{ $value ?? '-' }}</p>
</div>
