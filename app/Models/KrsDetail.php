<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KrsDetail extends Model
{
    protected $table = 'krs_detail';

    protected $fillable = [
        'krs_mahasiswa_id',
        'mata_kuliah_id',
    ];

    public function krsMahasiswa(): BelongsTo
    {
        return $this->belongsTo(KrsMahasiswa::class, 'krs_mahasiswa_id');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}
