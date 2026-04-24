{{-- KRS Package Card Component --}}
@props([
    'title' => '',
    'value' => '',
    'icon' => '',
    'accent' => 'bg-slate-100',
    'textColor' => 'text-slate-900',
])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">{{ $title }}</p>
            <p class="mt-3 text-3xl font-bold {{ $textColor }}">{{ $value }}</p>
        </div>
        <div class="p-3 rounded-2xl {{ $accent }} text-slate-700">
            {!! $icon !!}
        </div>
    </div>
</div>
