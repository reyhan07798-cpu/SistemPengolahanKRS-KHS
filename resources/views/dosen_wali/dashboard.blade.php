@extends('layouts.dosen_wali')

@section('content')
    <div class="heading">
        <h1>Beranda Dosen Wali</h1>
        <p>Selamat datang, Rusyda Nazhirah Yunus, S.S., M.Si</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Mahasiswa Bimbingan</div>
            <div class="stat-value">3</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">KRS Menunggu</div>
            <div class="stat-value">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">KRS Disetujui</div>
            <div class="stat-value">2</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">KRS Ditolak</div>
            <div class="stat-value">0</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Mahasiswa Bimbingan</div>
        </div>

        <div class="student-list">
            <div class="student-item">
                <div class="student-info">
                    <div class="student-avatar">R</div>
                    <div class="student-text">
                        <div class="student-name">Reyhan</div>
                        <div class="student-meta">3312501022 - Teknik Informatika - Kelas A</div>
                    </div>
                </div>
                <div class="student-values">
                    <div class="student-ipk"><strong>3.60</strong><span>IPK</span></div>
                    <div class="badge success">Disetujui</div>
                </div>
            </div>
            <div class="student-item">
                <div class="student-info">
                    <div class="student-avatar">N</div>
                    <div class="student-text">
                        <div class="student-name">Nabila Fatin</div>
                        <div class="student-meta">3312501007 - Teknik Informatika - Kelas A</div>
                    </div>
                </div>
                <div class="student-values">
                    <div class="student-ipk"><strong>3.60</strong><span>IPK</span></div>
                    <div class="badge success">Disetujui</div>
                </div>
            </div>
            <div class="student-item">
                <div class="student-info">
                    <div class="student-avatar">I</div>
                    <div class="student-text">
                        <div class="student-name">Irenessa Rosidin</div>
                        <div class="student-meta">3312501017 - Teknik Informatika - Kelas A</div>
                    </div>
                </div>
                <div class="student-values">
                    <div class="student-ipk"><strong>3.60</strong><span>IPK</span></div>
                    <div class="badge warning">Belum KRS</div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="summary-card">
            <h3>Ringkasan Akademik</h3>
            <div class="summary-list">
                <div class="summary-item">
                    Mahasiswa dengan IPK ≥ 3.0
                    <span class="summary-pill">2 dari 3</span>
                </div>
                <div class="summary-item">
                    Total KRS Disetujui
                    <strong>2</strong>
                </div>
                <div class="summary-item">
                    Total KRS Menunggu
                    <strong>0</strong>
                </div>
            </div>
        </div>

        <div class="distribution-card">
            <h3>Distribusi Kelas</h3>
            <div class="distribution-row">
                <div class="distribution-label">Kelas A</div>
                <div class="distribution-bar">
                    <span class="distribution-fill"></span>
                </div>
                <div class="distribution-number">3</div>
            </div>
        </div>
    </div>
@endsection
