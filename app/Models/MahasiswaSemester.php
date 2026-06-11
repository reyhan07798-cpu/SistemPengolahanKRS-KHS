<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MahasiswaSemester extends Model
{
    protected $table = 'mahasiswa_semester';

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'semester_ke',
        'status',
        'catatan',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function semesterData(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
