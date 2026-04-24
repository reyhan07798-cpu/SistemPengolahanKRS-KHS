<div class="{{ $accent }} rounded-2xl p-6 shadow-sm border border-gray-200">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $title }}</p>
            <p class="mt-4 text-4xl font-bold {{ $textColor }}">{{ $value }}</p>
        </div>
        @if($icon)
            <div class="flex-shrink-0">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
