<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_penempatan_kelas', function (Blueprint $table) {
            $table->foreignId('dilakukan_oleh')
                ->nullable()
                ->after('alasan_penempatan')
                ->constrained('users')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_penempatan_kelas', function (Blueprint $table) {
            $table->dropForeign(['dilakukan_oleh']);
            $table->dropColumn('dilakukan_oleh');
        });
    }
};