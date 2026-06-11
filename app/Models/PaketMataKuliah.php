<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketMataKuliah extends Model
{
    use SoftDeletes;

    protected $table = 'paket_mata_kuliahs';

    protected $fillable = [
        'nama_paket',
        'semester_id',
        'prodi_id',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
