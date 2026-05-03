@props([
    'kode_mk' => '',
    'nama_mk' => '',
    'sks' => '',
    'nilai' => '',
    'mutu' => 0,
    'bobot' => '',
    'tahun_ajaran' => ''
])

@php
    $nilaiBadge = match ($nilai) {
        'A', 'A-' => 'nb-badge-success',
        'B+', 'B' => 'nb-badge-primary',
        'B-', 'C+', 'C' => 'nb-badge-warning',
        default => 'nb-badge-danger',
    };
    $mutuClass = $mutu >= 3.5 ? 'text-accent' : ($mutu >= 2.5 ? 'text-primary' : 'text-muted');
@endphp

<tr>
    <td class="font-bold text-primary" style="font-family: var(--font-heading);">
        {{ $kode_mk }}
    </td>
    <td class="font-medium text-ink">{{ $nama_mk }}</td>
    <td class="text-center">{{ $sks }}</td>
    <td class="text-center">
        <span class="nb-badge {{ $nilaiBadge }}">{{ $nilai }}</span>
    </td>
    <td class="text-center">
        <span class="font-extrabold {{ $mutuClass }}" style="font-family:var(--font-heading);">{{ number_format($mutu, 2) }}</span>
    </td>
    <td class="text-center font-bold text-primary">{{ $bobot }}</td>
    <td class="text-center text-muted">{{ $tahun_ajaran }}</td>
</tr>

