<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cpl extends Model
{
    protected $table = 'cpl';

    protected $fillable = [
        'kurikulum_id',
        'kode',
        'domain',
        'deskripsi',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function cpmk(): BelongsToMany
    {
        return $this->belongsToMany(Cpmk::class, 'cpmk_cpl_map')->withPivot('bobot');
    }
}