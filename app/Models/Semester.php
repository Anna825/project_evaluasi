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

    /**
     * Setiap semester dimiliki oleh satu tahun akademik.
     */
    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    /**
     * Satu semester punya banyak kelas.
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    /**
     * Satu semester punya banyak mahasiswa_status_log.
     */
    public function mahasiswaStatusLog(): HasMany
    {
        return $this->hasMany(MahasiswaStatusLog::class);
    }
}