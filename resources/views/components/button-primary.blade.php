@props(['size' => 'md', 'variant' => 'primary', 'icon' => '', 'loading' => false])

@php
  $sizeClass = match($size) {
    'sm' => 'px-3 py-1.5 text-sm',
    'lg' => 'px-6 py-2.5 text-lg',
    default => 'px-4 py-2',
  };
@endphp

<button {{ $attributes->merge([
  'class' => "nb-btn nb-btn-{$variant} {$sizeClass} flex items-center gap-2 font-medium rounded-lg transition-all",
]) }} {{ $loading ? 'disabled' : '' }}>
  @if($loading)
    <span class="animate-spin"><span class="material-symbols-outlined">autorenew</span></span>
    Loading...
  @else
    @if($icon)
      <span class="material-symbols-outlined">{{ $icon }}</span>
    @endif
    {{ $slot }}
  @endif
</button>

