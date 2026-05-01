@props([
    'title' => 'Pengumuman',
    'description' => 'Sistem dalam pemeliharaan rutin setiap Minggu pukul 02.00-04.00 WIB.',
    'storageKey' => 'sipakar:announce:v1',
    'href' => '#',
    'showDot' => true,
])

<div class="nb-infocard" id="{{ $storageKey }}" data-storage-key="{{ $storageKey }}">
    @if($showDot)
        <span class="nb-infocard-dot" aria-hidden="true"></span>
    @endif
    <div class="nb-infocard-title">{{ $title }}</div>
    <div class="nb-infocard-desc">{{ $description }}</div>
    <div class="nb-infocard-footer">
        <button type="button" class="nb-infocard-action nb-infocard-action--dismiss" data-infocard-dismiss>
            Tutup
        </button>
        @if($href !== '#')
            <a href="{{ $href }}" class="nb-infocard-action">
                Selengkapnya
                <span class="material-symbols-outlined" style="font-size:12px;">open_in_new</span>
            </a>
        @endif
    </div>
</div>
