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
        Schema::create('riwayat_penempatan_kelas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa')
                ->cascadeOnDelete();

            $table->foreignId('kelas_mahasiswa_id')
                ->constrained('kelas_mahasiswa')
                ->restrictOnDelete();

            $table->foreignId('semester_id')
                ->constrained('semester')
                ->restrictOnDelete();

            $table->string('alasan_penempatan')->default('penempatan_awal');

            $table->timestamps();

            $table->unique(
                ['mahasiswa_id', 'semester_id'],
                'riwayat_penempatan_mahasiswa_semester_unique'
            );

            $table->index(['kelas_mahasiswa_id', 'semester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penempatan_kelas');
    }
};
