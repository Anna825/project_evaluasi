<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'mata_kuliah_id',
        'semester_id',
        'dosen_pengampu_id',
        'nama',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function dosenPengampu(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pengampu_id');
    }

    public function nilaiCpmk(): HasMany
    {
        return $this->hasMany(NilaiCpmk::class);
    }
}