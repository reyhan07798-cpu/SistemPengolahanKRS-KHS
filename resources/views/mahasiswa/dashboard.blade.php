@extends('layouts.mahasiswa')

@section('title', 'Beranda Mahasiswa - SIPAKAR')

@section('content')
<div class="heading">
    <h1>Beranda Mahasiswa</h1>
    <p>Selamat datang, {{ $data['nama'] }}</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Semester Aktif</div>
        <div class="stat-value">{{ $data['semester_aktif'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total SKS Diambil</div>
        <div class="stat-value">{{ $data['total_sks'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">IPK</div>
        <div class="stat-value">{{ $data['ipk'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Mata Kuliah Lulus</div>
        <div class="stat-value">{{ $data['mata_kuliah_lulus'] }}</div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Informasi Mahasiswa</div>
        </div>
        <table class="info-table">
            <tr>
                <td>NIM</td>
                <td>: {{ $data['nim'] }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>: {{ $data['nama'] }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: {{ $data['prodi'] }}</td>
            </tr>
            <tr>
                <td>Angkatan</td>
                <td>: {{ $data['angkatan'] }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $data['email'] }}</td>
            </tr>
        </table>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Nilai Terbaru</div>
        </div>
        <div class="nilai-list">
            @foreach(array_slice($data['nilai_terbaru'], 0, 4) as $nilai)
            <div class="nilai-item">
                <div class="nilai-matkul">{{ $nilai['matkul'] }}</div>
                <div class="nilai-bobot">
                    <span style="color: #6b7280; font-size: 0.85rem;">{{ $nilai['sks'] }} SKS</span>
                    <div class="nilai-badge">{{ $nilai['nilai'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="table-panel">
    <div class="table-header">
        <div class="table-title">KRS Aktif</div>
    </div>
    <table class="krs-table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['krs_aktif'] as $krs)
            <tr>
                <td>{{ $krs['kode'] }}</td>
                <td>{{ $krs['matkul'] }}</td>
                <td>{{ $krs['sks'] }}</td>
                <td>
                    <span class="status-badge {{ $krs['status'] == 'Disetujui' ? 'status-disetujui' : 'status-ditolak' }}">
                        {{ $krs['status'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection