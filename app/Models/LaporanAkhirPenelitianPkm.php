<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanAkhirPenelitianPkm extends Model
{
    protected $table = 'laporan_akhir_penelitian_pkm';

    protected $fillable = [
        'penelitian_pkm_id',
        'link_laporan',
        'tanggal_upload',
    ];

    public function penelitianPkm(): BelongsTo
    {
        return $this->belongsTo(PenelitianPkm::class);
    }
}