<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracerStudy extends Model
{
    protected $table = 'tracer_study';

    protected $fillable = [
        'mahasiswa_id',
        'tanggal_pengisian',
        'periode_pelacakan',
        'jenis_pengisian',

        // Data Alumni
        'nama_alumni',
        'tahun_masuk',
        'tahun_lulus',
        'prodi_id',

        // Pendidikan Lanjutan S2
        'melanjutkan_s2',
        'perguruan_tinggi_s2',
        'prodi_s2',

        // Pekerjaan Pertama
        'waktu_tunggu_kerja_pertama',
        'sumber_lowongan_pertama',
        'sumber_lowongan_pertama_lainnya',
        'jenis_pekerjaan_pertama',
        'jenis_pekerjaan_pertama_lainnya',
        'nama_instansi_pertama',
        'tingkat_perusahaan_pertama',
        'kesesuaian_bidang_pertama',
        'gaji_pertama',
        'posisi_pertama',

        // Pekerjaan Saat Ini / Terakhir
        'jenis_pekerjaan_saat_ini',
        'jenis_pekerjaan_saat_ini_lainnya',
        'nama_instansi_saat_ini',
        'tingkat_perusahaan_saat_ini',
        'jumlah_pindah_kerja',
        'alasan_pindah',
        'alasan_pindah_lainnya',
        'sumber_lowongan_saat_ini',
        'sumber_lowongan_saat_ini_lainnya',
        'gaji_saat_ini',
        'posisi_saat_ini',
    ];

    protected $casts = [
        'tanggal_pengisian' => 'date',
        'tahun_masuk' => 'integer',
        'tahun_lulus' => 'integer',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }
}