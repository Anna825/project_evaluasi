<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MahasiswaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Mahasiswa::with('prodi')->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return ['NIM', 'Nama', 'Program Studi', 'Angkatan', 'IPK Terakhir', 'Status'];
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