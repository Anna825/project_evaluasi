<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelasMahasiswa extends Model
{
    protected $table = 'kelas_mahasiswa';

    protected $fillable = [
        'prodi_id',
        'nama_kelas',
        'angkatan',
        'status_kelas',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'kelas_mahasiswa_id');
    }
}