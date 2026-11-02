<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatAksiAkademik extends Model
{
    protected $table = 'riwayat_aksi_akademik';

    protected $fillable = [
        'mahasiswa_id', 'jenis_aksi', 'data_sebelum', 'data_sesudah',
        'alasan', 'dilakukan_oleh',
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function pelaksana(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}
