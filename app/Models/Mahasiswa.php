<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Nama tabel di database (opsional jika nama tabel sudah jamak 'mahasiswas')
    protected $table = 'mahasiswas';

    // Field yang boleh diisi
    protected $fillable = [
        'nim',
        'nama',
        'kelas',
        'prodi',
        'angkatan',
        'ipk',
    ];
}