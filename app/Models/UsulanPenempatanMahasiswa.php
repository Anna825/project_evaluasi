<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulanPenempatanMahasiswa extends Model
{
    protected $table = 'usulan_penempatan_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'kelas_asal_id',
        'kelas_tujuan_id',
        'semester_id',
        'jenis_usulan',
        'status_usulan',
        'diajukan_oleh',
        'diajukan_pada',
        'diputuskan_oleh',
        'diputuskan_pada',
        'alasan_usulan',
        'catatan_keputusan',
    ];

    protected $casts = [
        'diajukan_pada' => 'datetime',
        'diputuskan_pada' => 'datetime',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function kelasAsal(): BelongsTo
    {
        return $this->belongsTo(KelasMahasiswa::class, 'kelas_asal_id');
    }

    public function kelasTujuan(): BelongsTo
    {
        return $this->belongsTo(KelasMahasiswa::class, 'kelas_tujuan_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function pemutus(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diputuskan_oleh');
    }
}