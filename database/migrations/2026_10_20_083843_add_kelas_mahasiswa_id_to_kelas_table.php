<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\HasMany;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('kelas_mahasiswa_id')
                ->nullable()
                ->after('mata_kuliah_id')
                ->constrained('kelas_mahasiswa')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['kelas_mahasiswa_id']);
            $table->dropColumn('kelas_mahasiswa_id');
        });
    }

    public function kelasMahasiswa(): BelongsTo
    {
        return $this->belongsTo(
            KelasMahasiswa::class,
            'kelas_mahasiswa_id'
        );
    }
};
