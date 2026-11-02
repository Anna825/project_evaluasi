<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_cuti_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('semester_mulai_id')->nullable()->constrained('semester')->nullOnDelete();
            $table->foreignId('semester_selesai_id')->nullable()->constrained('semester')->nullOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['mahasiswa_id', 'tanggal_mulai']);
        });

        Schema::create('riwayat_aksi_akademik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->nullable()->constrained('mahasiswa')->nullOnDelete();
            $table->string('jenis_aksi', 60);
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->text('alasan')->nullable();
            $table->foreignId('dilakukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['mahasiswa_id', 'jenis_aksi']);
        });

        Schema::create('riwayat_batch_kenaikan_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodi')->cascadeOnDelete();
            $table->foreignId('kelas_asal_id')->constrained('kelas_mahasiswa')->restrictOnDelete();
            $table->foreignId('semester_tujuan_id')->constrained('semester')->restrictOnDelete();
            $table->string('status', 20)->default('selesai');
            $table->unsignedInteger('jumlah_mahasiswa')->default(0);
            $table->foreignId('dilakukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['kelas_asal_id', 'semester_tujuan_id'], 'uniq_batch_kelas_semester');
        });

        Schema::create('riwayat_kelulusan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->date('tanggal_lulus');
            $table->text('catatan')->nullable();
            $table->foreignId('ditetapkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique('mahasiswa_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelulusan_mahasiswa');
        Schema::dropIfExists('riwayat_batch_kenaikan_kelas');
        Schema::dropIfExists('riwayat_aksi_akademik');
        Schema::dropIfExists('riwayat_cuti_mahasiswa');
    }
};
