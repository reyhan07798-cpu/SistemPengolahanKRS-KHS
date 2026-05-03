@props(['title' => '', 'description' => '', 'icon' => 'announcement', 'color' => 'nb-info'])

<div class="nb-card">
  <div class="flex items-start gap-4 p-6">
    <div class="nb-stat-icon">
      <span class="material-symbols-outlined">{{ $icon }}</span>
    </div>
    <div class="flex-1">
      <h4 class="nb-h4 font-bold mb-2">{{ $title }}</h4>
      <p class="text-muted">{{ $description }}</p>
    </div>
  </div>
</div>

