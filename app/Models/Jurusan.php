<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
        'nama',
        'kajur',
    ];

    /**
     * Satu jurusan punya banyak prodi.
     */
    public function prodi(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }
}