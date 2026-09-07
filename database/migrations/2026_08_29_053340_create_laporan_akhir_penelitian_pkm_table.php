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
        Schema::create('laporan_akhir_penelitian_pkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penelitian_pkm_id')->constrained('penelitian_pkm')->onDelete('cascade');
            $table->string('link_laporan');
            $table->date('tanggal_upload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_akhir_penelitian_pkm');
    }
};
