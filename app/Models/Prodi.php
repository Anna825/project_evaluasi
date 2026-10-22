<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
     protected $table = 'prodi';

    protected $fillable = [
        'jurusan_id',
        'nama',
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }

    public function kurikulum(): HasMany
    {
        return $this->hasMany(Kurikulum::class);
    }
}