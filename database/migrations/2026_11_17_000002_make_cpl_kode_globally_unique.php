<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Kode CPL unik dalam satu kurikulum, bukan secara global.
     *
     * Constraint gabungan (kurikulum_id, kode) sudah dibuat oleh
     * migration 2026_11_17_000001_add_unique_kurikulum_kode_to_cpl_table.
     * Migration ini dipertahankan sebagai no-op agar riwayat migration
     * yang sudah terlanjur dibuat tetap konsisten.
     */
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
