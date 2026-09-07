<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HilirisasiPkm extends Model
{
    protected $table = 'hilirisasi_pkm';

    protected $fillable = [
        'penelitian_pkm_id',
        'bentuk_luaran',
        'status_hilirisasi',
    ];

    public function penelitianPkm(): BelongsTo
    {
        return $this->belongsTo(PenelitianPkm::class);
    }
}