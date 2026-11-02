<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;
use Illuminate\\Database\\Eloquent\\Relations\\HasMany;

class KelasMahasiswa extends Model
{
    protected $table = 'kelas_mahasiswa';

    protected $fillable = [
        'prodi_id',
        'nama_kelas',
        'tingkat',
        'angkatan',
        'status_kelas',
    ];

    public static function tingkatDariNama(string $namaKelas): int
    {
        if (!preg_match('/^(\\d+)/', trim($namaKelas), $matches)) {
            throw new \\InvalidArgumentException('Nama kelas harus diawali angka tingkat, misalnya 1AEA-1.');
        }

        return (int) $matches[1];
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'kelas_mahasiswa_id');
    }

    public function riwayatPenempatan(): HasMany
    {
        return $this->hasMany(RiwayatPenempatanKelas::class, 'kelas_mahasiswa_id');
    }

    public function usulanKelasAsal(): HasMany
    {
        return $this->hasMany(UsulanPenempatanMahasiswa::class, 'kelas_asal_id');
    }

    public function usulanKelasTujuan(): HasMany
    {
        return $this->hasMany(UsulanPenempatanMahasiswa::class, 'kelas_tujuan_id');
    }
}
