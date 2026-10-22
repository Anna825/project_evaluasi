<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $table = 'semester';

    protected $fillable = [
        'tahun_akademik_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function mahasiswaStatusLog(): HasMany
    {
        return $this->hasMany(MahasiswaStatusLog::class);
    }

    public function riwayatPenempatan(): HasMany
    {
        return $this->hasMany(
            RiwayatPenempatanKelas::class,
            'semester_id'
        );
    }
}