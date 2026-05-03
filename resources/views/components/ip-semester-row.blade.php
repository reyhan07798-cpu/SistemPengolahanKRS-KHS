@props([
    'sem' => '',
    'tahun' => '',
    'mk' => '',
    'sks' => '',
    'ips' => 0,
    'ipk' => 0,
    'predikat' => ''
])

@php
    $predikatClass = match(true) {
        $ips >= 3.75 => 'nb-badge-success',
        $ips >= 3.50 => 'nb-badge-primary',
        $ips >= 3.00 => 'nb-badge-warning',
        default => 'nb-badge-stable',
    };
    $ipsClass = $ips >= 3.5 ? 'text-accent' : ($ips >= 3.0 ? 'text-primary' : 'text-muted');
@endphp

<tr>
    <td class="text-center font-bold text-ink">Semester {{ $sem }}</td>
    <td class="text-center text-muted">{{ $tahun }}</td>
    <td class="text-center font-bold text-primary">{{ $mk }} MK</td>
    <td class="text-center font-bold text-primary">{{ $sks }} SKS</td>
    <td class="text-center">
        <span class="font-extrabold text-xl {{ $ipsClass }}" style="font-family:var(--font-heading);">{{ number_format($ips, 2) }}</span>
    </td>
    <td class="text-center">
        <span class="font-bold text-ink" style="font-family:var(--font-heading);">{{ number_format($ipk, 2) }}</span>
    </td>
    <td class="text-center">
        <span class="nb-badge {{ $predikatClass }}">{{ $predikat }}</span>
    </td>
</tr>

