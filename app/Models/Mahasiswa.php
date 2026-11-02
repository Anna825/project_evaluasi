<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\UsulanPenempatanMahasiswa;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'prodi_id',
        'kelas_mahasiswa_id',
        'nim',
        'nama',
        'ipk_terakhir',
        'status',
        'angkatan_masuk',
    ];
    
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(MahasiswaStatusLog::class);
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
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

    public function kelasMahasiswa(): BelongsTo
    {
        return $this->belongsTo(KelasMahasiswa::class, 'kelas_mahasiswa_id');
    }

    public function riwayatPenempatanKelas(): HasMany
    {
        return $this->hasMany(RiwayatPenempatanKelas::class);
    }

    public function riwayatAksiAkademik(): HasMany
    {
        return $this->hasMany(RiwayatAksiAkademik::class, 'mahasiswa_id')->latest();
    }

    public function riwayatCuti(): HasMany
    {
        return $this->hasMany(RiwayatCutiMahasiswa::class, 'mahasiswa_id');
    }

    public function progresStudi(): HasOne
    {
        return $this->hasOne(ProgresStudiMahasiswa::class);
    }

    public function getAngkatanAttribute()
    {
        return $this->angkatan_masuk;
    }

    public function prestasiDibimbing(): BelongsToMany
    {
        return $this->belongsToMany(
            Prestasi::class,
            'prestasi_mahasiswa_dosen'
        )->withPivot('dosen_id');
    }

    public function usulanPenempatan(): HasMany
    {
        return $this->hasMany(UsulanPenempatanMahasiswa::class, 'mahasiswa_id');
    }
}