<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketMataKuliah extends Model
{
    use HasFactory;

    protected $table = 'paket_mata_kuliahs';

    protected $fillable = [
        'nama_paket',
        'semester',
        'prodi',
        'total_sks',
        'jumlah_mk',
        'deskripsi',
        'mata_kuliah',
    ];

    protected $casts = [
        'mata_kuliah' => 'array',
    ];
}
