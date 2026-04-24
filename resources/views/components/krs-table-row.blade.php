@props([
    'kode' => '',
    'matkul' => '',
    'sks' => '',
    'status' => ''
])

@php
    $statusColor = match($status) {
        'Disetujui' => 'bg-green-100 text-green-700',
        'Menunggu' => 'bg-orange-100 text-orange-700',
        'Ditolak' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp

<tr class="hover:bg-gray-50 transition">
    <td class="px-6 py-4 text-sm font-mono text-dark">{{ $kode }}</td>
    <td class="px-6 py-4 text-sm text-dark">{{ $matkul }}</td>
    <td class="px-6 py-4 text-sm text-center">{{ $sks }}</td>
    <td class="px-6 py-4 text-center">
        <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusColor }}">
            {{ $status }}
        </span>
    </td>
</tr>