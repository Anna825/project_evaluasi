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
        Schema::create('progres_studi_mahasiswa', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa')
                ->cascadeOnDelete();

            $table->unsignedInteger('semester_terakhir_selesai')->default(0);

            $table->unsignedInteger('semester_berikutnya')
                ->default(1);

            $table->foreignId('semester_terakhir_id')
                ->nullable()
                ->constrained('semester')
                ->nullOnDelete();

            $table->string('status_progres')->default('berjalan');

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique('mahasiswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progres_studi_mahasiswa');
    }
};
