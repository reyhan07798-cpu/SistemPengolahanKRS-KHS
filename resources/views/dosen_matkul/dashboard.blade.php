@extends('layout.dosen_matkul')

@section('content')

<div class="topbar">
    <div>
        <h2>Beranda Dosen Mata Kuliah</h2>
        <p>Selamat datang, Cyntia Lasmi Andesti</p>
    </div>
    <input type="text" placeholder="Cari mata kuliah..." style="padding:8px;border-radius:8px;border:1px solid #ccc;">
</div>

<div class="cards">
    <div class="card">
        <h4>Mata Kuliah Diampu</h4>
        <h2>3</h2>
    </div>
    <div class="card">
        <h4>Total Mahasiswa</h4>
        <h2>3</h2>
    </div>
    <div class="card">
        <h4>Nilai Sudah Diinput</h4>
        <h2>3</h2>
    </div>
    <div class="card">
        <h4>Belum Dinilai</h4>
        <h2>0</h2>
    </div>
</div>

<div class="section">
    <h3>Mata Kuliah yang Diampu</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Mata Kuliah</th>
            <th>Semester</th>
            <th>SKS</th>
            <th>Jadwal</th>
            <th>Ruang</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Pemrograman Dasar</td>
            <td><span class="badge badge-blue">Semester 1</span></td>
            <td>3</td>
            <td>Senin, 08:00</td>
            <td>Lab Komputer 2</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Basis Data</td>
            <td><span class="badge badge-green">Semester 2</span></td>
            <td>3</td>
            <td>Rabu, 10:00</td>
            <td>R.301</td>
        </tr>
        <tr>
            <td>3</td>
            <td>PBO</td>
            <td><span class="badge badge-purple">Semester 3</span></td>
            <td>3</td>
            <td>Jumat, 13:00</td>
            <td>R.204</td>
        </tr>
    </table>
</div>

<div class="section">
    <h3>Mahasiswa Terbaru</h3>
    <table>
        <tr>
            <th>Nama</th>
            <th>IPK</th>
        </tr>
        <tr>
            <td>Reyhan</td>
            <td>3.67</td>
        </tr>
        <tr>
            <td>Nabila Fatin</td>
            <td>3.50</td>
        </tr>
        <tr>
            <td>Irenessa Rosidin</td>
            <td>3.45</td>
        </tr>
    </table>
</div>

@endsection