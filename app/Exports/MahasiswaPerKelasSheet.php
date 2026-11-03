<?php

namespace App\Exports;

use App\Models\KelasMahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MahasiswaPerKelasSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private KelasMahasiswa $kelas)
    {
    }

    public function collection()
    {
        return $this->kelas->mahasiswa()
            ->with('prodi')
            ->orderBy('nama')
            ->get();
    }

    public function headings(): array
    {
        return ['NIM', 'Nama', 'Program Studi', 'Kelas', 'Angkatan Masuk', 'Status'];
    }

    public function map($mahasiswa): array
    {
        return [
            $mahasiswa->nim,
            $mahasiswa->nama,
            $mahasiswa->prodi->nama ?? '-',
            $this->kelas->nama_kelas,
            $mahasiswa->angkatan_masuk ?? '-',
            ucfirst($mahasiswa->status),
        ];
    }

    public function title(): string
    {
        $prefix = $this->kelas->prodi->nama ?? 'Prodi';
        $title = preg_replace('/[\\\\\/\?\*\[\]:]/', '-', $prefix . ' ' . $this->kelas->nama_kelas);
        return mb_substr($title, 0, 31);
    }
}
