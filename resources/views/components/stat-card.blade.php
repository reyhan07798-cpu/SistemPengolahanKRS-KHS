{{-- Stat Card Component for Mahasiswa Dashboard --}}
@props([
    'title' => '',
    'value' => '',
    'icon' => '',
    'bgColor' => 'bg-gray-100',
    'textColor' => 'text-gray-500'
])

<!-- Tambahkan h-full -->
<div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="flex justify-between items-start">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $title }}</p>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mt-1">{{ $value }}</h2>
        </div>
        <div class="p-2 {{ $bgColor }} rounded-md {{ $textColor }}">
            {!! $icon !!}
        </div>
    </div>
</div>