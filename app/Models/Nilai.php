<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'nilai',
        'bobot',
        'sks',
        'semester',
        'tahun_ajaran',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
