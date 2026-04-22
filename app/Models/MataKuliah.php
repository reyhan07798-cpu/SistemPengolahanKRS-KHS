<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'kode_mk',
        'nama',
        'sks',
        // tambahkan field lain jika ada
    ];
}