<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('tracer_study');

        Schema::create('tracer_study', function (Blueprint $table) {
            $table->id();

            // Relasi ke mahasiswa
            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa')
                ->onDelete('cascade');

            // Informasi pengisian
            $table->date('tanggal_pengisian');
            $table->string('periode_pelacakan', 20);
            $table->string('jenis_pengisian', 30);

            // ==========================================
            // DATA ALUMNI
            // ==========================================
            $table->string('nama_alumni');
            $table->unsignedInteger('tahun_masuk');
            $table->unsignedInteger('tahun_lulus');
            $table->foreignId('prodi_id')
                ->constrained('prodi')
                ->onDelete('restrict');

            // ==========================================
            // PENDIDIKAN LANJUTAN S2
            // ==========================================
            $table->string('melanjutkan_s2', 10)->nullable();
            $table->string('perguruan_tinggi_s2')->nullable();
            $table->string('prodi_s2')->nullable();

            // ==========================================
            // PEKERJAAN PERTAMA
            // ==========================================
            $table->string('waktu_tunggu_kerja_pertama')->nullable();

            $table->string('sumber_lowongan_pertama')->nullable();
            $table->string('sumber_lowongan_pertama_lainnya')->nullable();

            $table->string('jenis_pekerjaan_pertama')->nullable();
            $table->string('jenis_pekerjaan_pertama_lainnya')->nullable();

            $table->string('nama_instansi_pertama')->nullable();
            $table->string('tingkat_perusahaan_pertama')->nullable();

            $table->string('kesesuaian_bidang_pertama')->nullable();

            $table->string('gaji_pertama')->nullable();

            $table->string('posisi_pertama')->nullable();

            // ==========================================
            // PEKERJAAN SAAT INI / TERAKHIR
            // ==========================================
            $table->string('jenis_pekerjaan_saat_ini')->nullable();
            $table->string('jenis_pekerjaan_saat_ini_lainnya')->nullable();

            $table->string('nama_instansi_saat_ini')->nullable();
            $table->string('tingkat_perusahaan_saat_ini')->nullable();

            $table->string('jumlah_pindah_kerja')->nullable();

            $table->string('alasan_pindah')->nullable();
            $table->string('alasan_pindah_lainnya')->nullable();

            $table->string('sumber_lowongan_saat_ini')->nullable();
            $table->string('sumber_lowongan_saat_ini_lainnya')->nullable();

            $table->string('gaji_saat_ini')->nullable();

            $table->string('posisi_saat_ini')->nullable();

            // Timestamp teknis Laravel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_study');
    }
};