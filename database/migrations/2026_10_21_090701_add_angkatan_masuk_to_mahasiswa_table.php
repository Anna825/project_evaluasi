
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->integer('angkatan_masuk')->nullable();
        });

        DB::statement("
            UPDATE mahasiswa
            SET angkatan_masuk = kelas_mahasiswa.angkatan
            FROM kelas_mahasiswa
            WHERE mahasiswa.kelas_mahasiswa_id = kelas_mahasiswa.id
        ");
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('angkatan_masuk');
        });
    }
};
