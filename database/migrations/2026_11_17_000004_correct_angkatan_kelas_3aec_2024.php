<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Koreksi angkatan pada dua kelas AEC yang salah tercatat sebagai 2023.
     * Hanya menyentuh kelas yang dimaksud pada Program Studi terkait.
     */
    public function up(): void
    {
        $prodiIds = DB::table('prodi')
            ->where('nama', 'Teknologi Rekayasa Informatika Industri')
            ->select('id');

        DB::table('kelas_mahasiswa')
            ->whereIn('prodi_id', $prodiIds)
            ->whereIn('nama_kelas', ['3AEC-2', '3AEC-4'])
            ->where('angkatan', 2023)
            ->update([
                'angkatan' => 2024,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        $prodiIds = DB::table('prodi')
            ->where('nama', 'Teknologi Rekayasa Informatika Industri')
            ->select('id');

        DB::table('kelas_mahasiswa')
            ->whereIn('prodi_id', $prodiIds)
            ->whereIn('nama_kelas', ['3AEC-2', '3AEC-4'])
            ->where('angkatan', 2024)
            ->update([
                'angkatan' => 2023,
                'updated_at' => now(),
            ]);
    }
};
