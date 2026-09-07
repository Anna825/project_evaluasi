<?php

namespace App\Exports;

use App\Models\Prestasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrestasiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Prestasi::with('mahasiswa', 'dosen', 'tahunAkademik')->latest()->get();
    }

    public function headings(): array
    {
        return ['Nama Kegiatan', 'Tingkat', 'Jenis', 'Peringkat', 'Tahun Akademik', 'Atas Nama'];
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