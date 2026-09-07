<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dosen extends Model
{
    protected $table = 'dosen';

    protected $fillable = [
        'user_id',
        'prodi_id',
        'nidn',
        'nama',
        'jenis_kelamin',
        'jabatan_fungsional',
        'pendidikan_terakhir',
        'institusi_lulusan',
        'no_hp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function jabatanLog(): HasMany
    {
        return $this->hasMany(DosenJabatanLog::class);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'dosen_pengampu_id');
    }

    public function rps(): HasMany
    {
        return $this->hasMany(Rps::class);
    }

    public function penelitianPkm(): BelongsToMany
    {
        return $this->belongsToMany(PenelitianPkm::class, 'penelitian_pkm_dosen')->withPivot('peran');
    }

    public function prestasi(): BelongsToMany
    {
        return $this->belongsToMany(Prestasi::class, 'prestasi_dosen');
    }
}