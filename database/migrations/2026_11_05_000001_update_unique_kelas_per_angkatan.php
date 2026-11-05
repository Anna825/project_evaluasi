<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_mahasiswa', function (Blueprint $table) {
            $table->dropUnique('kelas_mahasiswa_prodi_id_nama_kelas_unique');
            $table->unique(
                ['prodi_id', 'nama_kelas', 'angkatan'],
                'kelas_mahasiswa_prodi_nama_angkatan_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('kelas_mahasiswa', function (Blueprint $table) {
            $table->dropUnique('kelas_mahasiswa_prodi_nama_angkatan_unique');
            $table->unique(
                ['prodi_id', 'nama_kelas'],
                'kelas_mahasiswa_prodi_id_nama_kelas_unique'
            );
        });
    }
};
