<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $fillable = [
        'label',
    ];

    /**
     * Satu tahun akademik punya banyak semester.
     */
    public function semester(): HasMany
    {
        return $this->hasMany(Semester::class);
    }
}