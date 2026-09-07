<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MahasiswaStatusLog extends Model
{
    protected $table = 'mahasiswa_status_log';

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'status',
        'catatan',
        'dicatat_oleh',
        'dicatat_pada',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}