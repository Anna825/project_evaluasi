<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MahasiswaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = Auth::user();

        // Admin dapat melihat seluruh mahasiswa.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        $query = Mahasiswa::with('prodi');

        if (! $isAdmin) {
            // Kaprodi hanya mendapatkan mahasiswa
            // dari Prodi yang menjadi tanggung jawabnya.
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $query->where('prodi_id', $prodiId);
        }

        return $query
            ->orderBy('nama')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'Program Studi',
            'Angkatan',
            'IPK Terakhir',
            'Status',
        ];
    }

    public function map($mahasiswa): array
    {
        return [
            $mahasiswa->nim,
            $mahasiswa->nama,
            $mahasiswa->prodi->nama ?? '-',
            $mahasiswa->angkatan,
            $mahasiswa->ipk_terakhir ?? '-',
            ucfirst($mahasiswa->status),
        ];
    }
}