<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rps extends Model
{
    protected $table = 'rps';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'versi',
        'deskripsi_singkat',
        'tanggal_disusun',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}