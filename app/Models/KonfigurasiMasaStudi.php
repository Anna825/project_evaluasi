<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KonfigurasiMasaStudi extends Model
{
    protected $table = 'konfigurasi_masa_studi';

    protected $fillable = [
        'prodi_id',
        'masa_studi_standar_semester',
        'berlaku_mulai_angkatan',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }
}