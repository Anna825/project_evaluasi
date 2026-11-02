<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPenempatanKelas extends Model
{
    protected $table = 'riwayat_penempatan_kelas';

    protected $fillable = [
        'mahasiswa_id',
        'kelas_mahasiswa_id',
        'semester_id',
        'alasan_penempatan',
        'dilakukan_oleh',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function kelasMahasiswa(): BelongsTo
    {
        return $this->belongsTo(KelasMahasiswa::class, 'kelas_mahasiswa_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function pelaksana(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}