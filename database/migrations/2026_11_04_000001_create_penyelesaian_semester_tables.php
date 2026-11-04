<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_batch_penyelesaian_semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodi')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->restrictOnDelete();
            $table->unsignedInteger('jumlah_mahasiswa')->default(0);
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['prodi_id', 'semester_id']);
        });

        Schema::create('riwayat_detail_penyelesaian_semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('riwayat_batch_penyelesaian_semester')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->restrictOnDelete();
            $table->unsignedSmallInteger('semester_diselesaikan');
            $table->unsignedSmallInteger('semester_berikutnya');
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'semester_id'], 'uniq_mahasiswa_penyelesaian_semester');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_detail_penyelesaian_semester');
        Schema::dropIfExists('riwayat_batch_penyelesaian_semester');
    }
};
