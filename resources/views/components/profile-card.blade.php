@props(['name' => '', 'role' => '', 'avatar' => '', 'stats' => []])

<div class="nb-card p-6 text-center">
  <div class="mx-auto w-24 h-24 mb-6">
    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-full h-full rounded-full object-cover border-4 border-primary">
  </div>
  <h3 class="nb-h3 mb-2">{{ $name }}</h3>
  <p class="nb-badge mb-6">{{ $role }}</p>
  
  @if($stats)
    <div class="grid grid-cols-3 gap-4">
      @foreach($stats as $stat)
        <div>
          <p class="nb-stat-value">{{ $stat['value'] }}</p>
          <p class="text-sm text-muted">{{ $stat['label'] }}</p>
        </div>
      @endforeach
    </div>
  @endif
</div>

