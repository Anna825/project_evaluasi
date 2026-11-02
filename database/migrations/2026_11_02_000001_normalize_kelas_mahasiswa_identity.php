<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_mahasiswa', function (Blueprint $table) {
            $table->unsignedTinyInteger('tingkat')->nullable()->after('nama_kelas');
        });

        DB::transaction(function () {
            $classes = DB::table('kelas_mahasiswa')->orderBy('id')->get();

            foreach ($classes as $class) {
                if (!preg_match('/^(\\d+)/', $class->nama_kelas, $matches)) {
                    throw new RuntimeException("Kode kelas {$class->nama_kelas} tidak diawali angka tingkat.");
                }

                DB::table('kelas_mahasiswa')
                    ->where('id', $class->id)
                    ->update(['tingkat' => (int) $matches[1]]);
            }

            $duplicates = DB::table('kelas_mahasiswa')
                ->select('prodi_id', 'nama_kelas')
                ->groupBy('prodi_id', 'nama_kelas')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            foreach ($duplicates as $duplicate) {
                $ids = DB::table('kelas_mahasiswa')
                    ->where('prodi_id', $duplicate->prodi_id)
                    ->where('nama_kelas', $duplicate->nama_kelas)
                    ->orderByDesc('id')
                    ->pluck('id');

                $canonicalId = (int) $ids->first();
                $duplicateIds = $ids->slice(1)->map(fn ($id) => (int) $id)->all();

                if (!$duplicateIds) {
                    continue;
                }

                foreach (['mahasiswa', 'kelas', 'riwayat_penempatan_kelas'] as $table) {
                    DB::table($table)
                        ->whereIn('kelas_mahasiswa_id', $duplicateIds)
                        ->update(['kelas_mahasiswa_id' => $canonicalId]);
                }

                DB::table('usulan_penempatan_mahasiswa')
                    ->whereIn('kelas_asal_id', $duplicateIds)
                    ->update(['kelas_asal_id' => $canonicalId]);

                DB::table('usulan_penempatan_mahasiswa')
                    ->whereIn('kelas_tujuan_id', $duplicateIds)
                    ->update(['kelas_tujuan_id' => $canonicalId]);

                DB::table('kelas_mahasiswa')->whereIn('id', $duplicateIds)->delete();
            }
        });

        Schema::table('kelas_mahasiswa', function (Blueprint $table) {
            $table->dropUnique(['prodi_id', 'nama_kelas', 'angkatan']);
            $table->unique(['prodi_id', 'nama_kelas']);
        });

        DB::statement('ALTER TABLE kelas_mahasiswa ALTER COLUMN tingkat SET NOT NULL');
    }

    public function down(): void
    {
        Schema::table('kelas_mahasiswa', function (Blueprint $table) {
            $table->dropUnique(['prodi_id', 'nama_kelas']);
            $table->unique(['prodi_id', 'nama_kelas', 'angkatan']);
            $table->dropColumn('tingkat');
        });
    }
};
