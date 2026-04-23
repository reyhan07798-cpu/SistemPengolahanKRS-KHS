{{-- Info Box Component for Mahasiswa Dashboard --}}
@props([
    'label' => '',
    'value' => ''
])

<div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
    <span class="text-sm font-medium text-gray-600">{{ $label }}</span>
    <span class="text-sm font-semibold text-slate-900">{{ $value }}</span>
</div>