@props([
    'kode' => '',
    'matkul' => '',
    'sks' => '',
    'status' => ''
])

@php
    $statusBadge = match($status) {
        'Disetujui' => 'nb-badge-success',
        'Menunggu' => 'nb-badge-warning',
        'Ditolak' => 'nb-badge-danger',
        default => 'nb-badge-stable',
    };
@endphp

<tr>
    <td class="font-bold text-primary" style="font-family: var(--font-heading);">{{ $kode }}</td>
    <td class="font-medium text-ink">{{ $matkul }}</td>
    <td class="text-center">{{ $sks }}</td>
    <td class="text-center">
        <span class="nb-badge {{ $statusBadge }}">{{ $status }}</span>
    </td>
</tr>
