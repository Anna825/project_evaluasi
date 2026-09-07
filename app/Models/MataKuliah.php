<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kurikulum_id',
        'kode',
        'nama',
        'sks',
        'semester_ke',
        'jenis',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function rps(): HasMany
    {
        return $this->hasMany(Rps::class);
    }

    public function cpmk(): HasMany
    {
        return $this->hasMany(Cpmk::class);
    }
}