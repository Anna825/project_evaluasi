<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi_mahasiswa_dosen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prestasi_id')
                ->constrained('prestasi')
                ->onDelete('cascade');

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa')
                ->onDelete('cascade');

            $table->foreignId('dosen_id')
                ->constrained('dosen')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique([
                'prestasi_id',
                'mahasiswa_id',
                'dosen_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_mahasiswa_dosen');
    }
};