<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cpl', function (Blueprint $table) {
            $table->dropUnique('cpl_kurikulum_id_kode_unique');
            $table->unique('kode', 'cpl_kode_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cpl', function (Blueprint $table) {
            $table->dropUnique('cpl_kode_unique');
            $table->unique(['kurikulum_id', 'kode'], 'cpl_kurikulum_id_kode_unique');
        });
    }
};
