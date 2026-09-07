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

    /**
     * Setiap prodi dimiliki oleh satu jurusan.
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Satu prodi punya banyak mahasiswa.
     */
    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }

    /**
     * Satu prodi punya banyak dosen.
     */
    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }

    /**
     * Satu prodi punya banyak kurikulum.
     */
    public function kurikulum(): HasMany
    {
        return $this->hasMany(Kurikulum::class);
    }
}