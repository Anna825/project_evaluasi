<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresStudiMahasiswa extends Model
{
    protected $table = 'progres_studi_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'semester_terakhir_selesai',
        'semester_berikutnya',
        'semester_terakhir_id',
        'status_progres',
        'catatan',
    ];

    protected $casts = [
        'semester_terakhir_selesai' => 'integer',
        'semester_berikutnya' => 'integer',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semesterTerakhir(): BelongsTo
    {
        return $this->belongsTo(
            Semester::class,
            'semester_terakhir_id'
        );
    }
}
