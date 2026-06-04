<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliah extends Model
{
    use SoftDeletes;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'dosen_id',
        'kode_mk',
        'nama',
        'sks',
        'semester_ke',
        'prasyarat',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
