<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penelitian_pkm', function (Blueprint $table) {
            $table->text('deskripsi_kegiatan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('penelitian_pkm', function (Blueprint $table) {
            $table->dropColumn('deskripsi_kegiatan');
        });
    }
};
