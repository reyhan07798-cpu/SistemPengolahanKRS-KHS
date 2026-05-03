@props([
    'title' => 'Mata Kuliah Wajib',
    'data' => [],
    'isMengulang' => false,
    'tbodyId' => 'tabelWajib',
    'containerId' => 'containerWajib'
])

<div id="{{ $containerId }}" class="nb-card-flat mb-6 hidden">
    <div class="nb-section-header" style="{{ $isMengulang ? 'background-color: var(--color-warning-soft)' : '' }}">
        <div>
            <span class="nb-eyebrow" style="color: {{ $isMengulang ? 'rgba(31,41,55,0.7)' : 'var(--color-accent-soft)' }};">
                {{ $isMengulang ? 'Mengulang' : 'Paket Semester ' }}<span id="label{{ ucfirst($containerId) }}"></span>
            </span>
            <h2 class="mt-1" style="{{ $isMengulang ? 'color: var(--color-ink)' : '' }}">{{ $title }}</h2>
        </div>
        @if($isMengulang)
            <span class="nb-badge nb-badge-stable">Nilai D / E</span>
        @else
            <span class="nb-badge nb-badge-success">Wajib Diambil</span>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead>
                <tr>
                    <th class="text-center">Pilih</th>
                    <th>Kode</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th class="text-center">SKS</th>
                    <th class="text-center">{{ $isMengulang ? 'Nilai Lama' : 'Prasyarat' }}</th>
                </tr>
            </thead>
            <tbody id="{{ $tbodyId }}">
                {{-- Filled by JavaScript --}}
            </tbody>
        </table>
    </div>
</div>

