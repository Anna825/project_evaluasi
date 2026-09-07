<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PenelitianPkm extends Model
{
    protected $table = 'penelitian_pkm';

    protected $fillable = [
        'judul',
        'jenis',
        'kategori_pendanaan',
        'tahun_akademik_id',
        'status',
    ];

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function dosen(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'penelitian_pkm_dosen')->withPivot('peran');
    }

    public function laporanAkhir(): HasOne
    {
        return $this->hasOne(LaporanAkhirPenelitianPkm::class);
    }

    public function hilirisasi(): HasOne
    {
        return $this->hasOne(HilirisasiPkm::class);
    }
}