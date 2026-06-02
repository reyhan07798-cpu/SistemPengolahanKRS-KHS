<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketMataKuliah extends Model
{
    protected $table = 'paket_mata_kuliahs';

    protected $fillable = [
        'nama_paket',
        'semester',
        'prodi',
        'total_sks',
        'jumlah_mk',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
