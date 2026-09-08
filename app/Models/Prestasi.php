<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prestasi extends Model
{
    protected $table = 'prestasi';

    protected $fillable = [
        'nama_kegiatan',
        'tingkat',
        'jenis',
        'peringkat',
        'tempat_pelaksanaan',
        'tahun_akademik_id',
    ];

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'prestasi_mahasiswa');
    }

    public function dosen(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'prestasi_dosen');
    }
}