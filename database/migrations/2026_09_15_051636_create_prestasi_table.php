<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();

            $table->string('nama_kegiatan');
            $table->string('tingkat')->nullable();
            $table->string('jenis')->nullable();
            $table->string('peringkat')->nullable();
            $table->string('tempat_pelaksanaan')->nullable();
            $table->date('tanggal_penerimaan')->nullable();

            $table->foreignId('tahun_akademik_id')
                ->constrained('tahun_akademik')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};