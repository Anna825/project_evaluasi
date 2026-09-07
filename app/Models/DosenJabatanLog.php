<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenJabatanLog extends Model
{
    protected $table = 'dosen_jabatan_log';

    protected $fillable = [
        'dosen_id',
        'jabatan_fungsional',
        'tanggal_mulai',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}