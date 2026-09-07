<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'prodi_id',
        'nim',
        'nama',
        'angkatan',
        'ipk_terakhir',
        'status',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(MahasiswaStatusLog::class);
    }

    public function nilaiCpmk(): HasMany
    {
        return $this->hasMany(NilaiCpmk::class);
    }

    public function tracerStudy(): HasMany
    {
        return $this->hasMany(TracerStudy::class);
    }

    public function prestasi(): BelongsToMany
    {
        return $this->belongsToMany(Prestasi::class, 'prestasi_mahasiswa');
    }
}