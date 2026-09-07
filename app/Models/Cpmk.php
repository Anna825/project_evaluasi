<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cpmk extends Model
{
    protected $table = 'cpmk';

    protected $fillable = [
        'mata_kuliah_id',
        'kode',
        'deskripsi',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function cpl(): BelongsToMany
    {
        return $this->belongsToMany(Cpl::class, 'cpmk_cpl_map')->withPivot('bobot');
    }

    public function nilaiCpmk(): HasMany
    {
        return $this->hasMany(NilaiCpmk::class);
    }
}