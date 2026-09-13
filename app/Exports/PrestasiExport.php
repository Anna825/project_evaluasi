<?php

namespace App\Exports;

use App\Models\Prestasi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrestasiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = Auth::user();

        // Admin dapat melihat seluruh prestasi.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        $query = Prestasi::with(
            'mahasiswa',
            'dosen',
            'tahunAkademik'
        );

        if (! $isAdmin) {
            // Ambil Prodi dari role Kaprodi.
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            /*
             * Kaprodi hanya mendapatkan prestasi yang terkait dengan:
             * - Mahasiswa dari Prodi sendiri, ATAU
             * - Dosen dari Prodi sendiri.
             */
            $query->where(function ($query) use ($prodiId) {
                $query
                    ->whereHas('mahasiswa', function ($mahasiswaQuery) use ($prodiId) {
                        $mahasiswaQuery->where('mahasiswa.prodi_id', $prodiId);
                    })
                    ->orWhereHas('dosen', function ($dosenQuery) use ($prodiId) {
                        $dosenQuery->where('dosen.prodi_id', $prodiId);
                    });
            });
        }

        return $query
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Kegiatan',
            'Tingkat',
            'Jenis',
            'Peringkat',
            'Tahun Akademik',
            'Atas Nama',
        ];
    }

    public function map($p): array
    {
        $atasNama = $p->mahasiswa->pluck('nama')
            ->merge($p->dosen->pluck('nama'))
            ->implode(', ');

        return [
            $p->nama_kegiatan,
            $p->tingkat ?? '-',
            $p->jenis ?? '-',
            $p->peringkat ?? '-',
            $p->tahunAkademik->label ?? '-',
            $atasNama ?: '-',
        ];
    }
}