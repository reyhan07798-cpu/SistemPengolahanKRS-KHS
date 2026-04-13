@extends('layouts.admin')

@section('title', 'Beranda Admin - SIPAKAR')

@section('content')
<div class="heading">
    <h1>Beranda Admin</h1>
    <p>Selamat datang di Sistem Informasi Pengelolaan Akademik KRS dan KHS</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Mahasiswa</div>
        <div class="stat-value">{{ $data['total_mahasiswa'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Dosen</div>
        <div class="stat-value">{{ $data['total_dosen'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Mata Kuliah</div>
        <div class="stat-value">{{ $data['total_matkul'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Rata-rata IPK</div>
        <div class="stat-value">{{ number_format($data['rata_ipk'], 2) }}</div>
    </div>
</div>

<!-- Ranking Table -->
<div class="panel">
    <div class="panel-header">
        <div>
            <div class="panel-title">Peringkat IPK Mahasiswa</div>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 4px;">Top 10 mahasiswa dengan IPK tertinggi</p>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #9ca3af; color: white;">
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Rank</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">NIM</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Nama Mahasiswa</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Kelas</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Prodi</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Angkatan</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">IPK</th>
                    <th style="padding: 12px 16px; text-align: left; font-weight: 600;">Predikat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['mahasiswa'] as $mhs)
                <tr style="border-bottom: 1px solid #e5e7eb; background: white;">
                    <td style="padding: 12px 16px;">
                        @if($mhs['rank'] == 1)
                            <span style="font-size: 1.5rem;">🥇</span>
                        @elseif($mhs['rank'] == 2)
                            <span style="font-size: 1.5rem;">🥈</span>
                        @elseif($mhs['rank'] == 3)
                            <span style="font-size: 1.5rem;">🥉</span>
                        @else
                            <span style="color: #6b7280; font-weight: 600;">{{ $mhs['rank'] }}</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px; font-family: monospace;">{{ $mhs['nim'] }}</td>
                    <td style="padding: 12px 16px; font-weight: 700;">{{ $mhs['nama'] }}</td>
                    <td style="padding: 12px 16px;">{{ $mhs['kelas'] }}</td>
                    <td style="padding: 12px 16px; font-size: 0.9rem;">{{ $mhs['prodi'] }}</td>
                    <td style="padding: 12px 16px;">{{ $mhs['angkatan'] }}</td>
                    <td style="padding: 12px 16px;">
                        @if($mhs['ipk'] != '-')
                            <span style="background: #e5e7eb; padding: 4px 12px; border-radius: 999px; font-weight: 700;">
                                {{ $mhs['ipk'] }}
                            </span>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px;">
                        @if($mhs['predikat'] == 'Cumlaude')
                            <span style="background: #10b981; color: white; padding: 6px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700;">
                                {{ $mhs['predikat'] }}
                            </span>
                        @elseif($mhs['predikat'] == 'Sangat Baik')
                            <span style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700;">
                                {{ $mhs['predikat'] }}
                            </span>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection