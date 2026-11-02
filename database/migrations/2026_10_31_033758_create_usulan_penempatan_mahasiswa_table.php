<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usulan_penempatan_mahasiswa', function (Blueprint $table) {
            $table->id();

            // Mahasiswa yang diajukan
            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa')
                ->restrictOnDelete();

            // Kelas asal dan kelas tujuan
            $table->foreignId('kelas_asal_id')
                ->nullable()
                ->constrained('kelas_mahasiswa')
                ->restrictOnDelete();

            $table->foreignId('kelas_tujuan_id')
                ->constrained('kelas_mahasiswa')
                ->restrictOnDelete();

            // Semester berlakunya penempatan
            $table->foreignId('semester_id')
                ->constrained('semester')
                ->restrictOnDelete();

            // Jenis dan status usulan
            $table->string('jenis_usulan');
            $table->string('status_usulan')->default('menunggu');

            // Pengusul
            $table->foreignId('diajukan_oleh')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('diajukan_pada')->nullable();

            // Keputusan Kaprodi
            $table->foreignId('diputuskan_oleh')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('diputuskan_pada')->nullable();

            $table->text('alasan_usulan')->nullable();
            $table->text('catatan_keputusan')->nullable();

            $table->timestamps();

            $table->index(['mahasiswa_id', 'status_usulan']);
            $table->index(['status_usulan', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_penempatan_mahasiswa');
    }
};
