<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kode mata kuliah boleh digunakan kembali pada kurikulum lain,
     * tetapi harus unik di dalam kurikulum yang sama.
     */
    public function up(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropUnique('mata_kuliah_kode_unique');

            $table->unique(
                ['kurikulum_id', 'kode'],
                'mata_kuliah_kurikulum_id_kode_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropUnique('mata_kuliah_kurikulum_id_kode_unique');

            $table->unique('kode', 'mata_kuliah_kode_unique');
        });
    }
};
