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
        'tanggal_penerimaan',
        'tahun_akademik_id',
    ];

    protected $casts = [
        'tanggal_penerimaan' => 'date',
    ];

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(
            Mahasiswa::class,
            'prestasi_mahasiswa'
        );
    }

    public function dosen(): BelongsToMany
    {
        return $this->belongsToMany(
            Dosen::class,
            'prestasi_dosen'
        );
    }

    public function dosenPembimbing(): BelongsToMany
    {
        return $this->belongsToMany(
            Dosen::class,
            'prestasi_mahasiswa_dosen'
        )->withPivot('mahasiswa_id');
    }
}