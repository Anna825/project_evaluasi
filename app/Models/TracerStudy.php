<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracerStudy extends Model
{
    protected $table = 'tracer_study';

    protected $fillable = [
        'mahasiswa_id',
        'periode_pelacakan',
        'status_utama',
        'nama_instansi',
        'kesesuaian_bidang',
        'rentang_pendapatan',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}