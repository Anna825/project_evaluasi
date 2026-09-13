<?php

namespace App\Exports;

use App\Models\PenelitianPkm;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenelitianPkmExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = Auth::user();

        // Admin dapat melihat seluruh Penelitian/PKM.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        $query = PenelitianPkm::with(
            'dosen',
            'tahunAkademik',
            'hilirisasi'
        );

        if (! $isAdmin) {
            // Kaprodi hanya dapat melihat Penelitian/PKM
            // yang Ketua-nya berasal dari Prodi sendiri.
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $query->whereHas('dosen', function ($query) use ($prodiId) {
                $query
                    ->where('dosen.prodi_id', $prodiId)
                    ->where(
                        'penelitian_pkm_dosen.peran',
                        'Ketua'
                    );
            });
        }

        return $query
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Judul',
            'Jenis',
            'Kategori Pendanaan',
            'Tahun Akademik',
            'Status',
            'Tim Dosen',
            'Bentuk Luaran',
        ];
    }

    public function map($p): array
    {
        return [
            $p->judul,
            $p->jenis,
            $p->kategori_pendanaan ?? '-',
            $p->tahunAkademik->label ?? '-',
            ucfirst($p->status),
            $p->dosen->pluck('nama')->implode(', '),
            $p->hilirisasi->bentuk_luaran ?? '-',
        ];
    }
}