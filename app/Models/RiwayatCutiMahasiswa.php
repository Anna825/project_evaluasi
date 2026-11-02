<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatCutiMahasiswa extends Model
{
    protected $table = 'riwayat_cuti_mahasiswa';

    protected $fillable = [
        'mahasiswa_id', 'semester_mulai_id', 'semester_selesai_id',
        'tanggal_mulai', 'tanggal_selesai', 'catatan', 'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semesterMulai(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_mulai_id');
    }

    public function semesterSelesai(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_selesai_id');
    }
}
