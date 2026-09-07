<?php

namespace App\Exports;

use App\Models\PenelitianPkm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenelitianPkmExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PenelitianPkm::with('dosen', 'tahunAkademik', 'hilirisasi')->latest()->get();
    }

    public function headings(): array
    {
        return ['Judul', 'Jenis', 'Kategori Pendanaan', 'Tahun Akademik', 'Status', 'Tim Dosen', 'Bentuk Luaran'];
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