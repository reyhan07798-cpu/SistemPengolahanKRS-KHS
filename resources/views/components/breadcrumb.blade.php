@props(['items' => []])

<nav class="flex" aria-label="Breadcrumb">
  <ol class="flex items-center space-x-2">
    @foreach($items as $index => $item)
      @if($index < count($items) - 1)
        <li>
          <div class="flex items-center">
            <a href="{{ $item['url'] }}" class="nb-link">{{ $item['label'] }}</a>
            <svg class="w-4 h-4 mx-1 text-muted" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
          </div>
        </li>
      @else
        <li>
          <span class="font-medium text-ink">{{ $item['label'] }}</span>
        </li>
      @endif
    @endforeach
  </ol>
</nav>

