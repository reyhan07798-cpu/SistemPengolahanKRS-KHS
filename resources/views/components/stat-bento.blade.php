
<div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
    @props(['stats' => [], 'config' => []])
    
    @foreach($stats as $key => $value)
        @php
            $conf = $config[$key] ?? [];
            $colorClass = $conf['color'] ?? 'nb-stat--info';
            $icon = $conf['icon'] ?? 'info';
            $label = $conf['label'] ?? ucwords(str_replace('_', ' ', $key));
        @endphp
        <div class="{{ $colorClass }} nb-stat nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">{{ $icon }}</span>
                </div>
                <p class="nb-stat-label">{{ $label }}</p>
            </div>
            <div class="nb-stat-value">{{ $value }}</div>
        </div>
    @endforeach
</div>

