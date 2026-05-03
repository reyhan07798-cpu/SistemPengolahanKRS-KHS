@props(['message' => '', 'type' => 'info'])

@php
  $alertClass = match($type) {
    'success' => 'nb-alert-success',
    'warning' => 'nb-alert-warning',
    'danger' => 'nb-alert-danger',
    default => 'nb-alert-info',
  };
@endphp

<div class="{{ $alertClass }} flex items-center gap-2 p-4 rounded-lg">
  <span class="material-symbols-outlined">info</span>
  <span>{{ $message }}</span>
</div>

