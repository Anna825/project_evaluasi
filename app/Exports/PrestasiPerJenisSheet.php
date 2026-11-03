<?php

namespace App\Exports;

use App\Models\Prestasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PrestasiPerJenisSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private string $jenis,
        private ?int $prodiId = null
    ) {
    }

    public function collection()
    {
        $relation = $this->jenis === 'mahasiswa' ? 'mahasiswa' : 'dosen';
        $query = Prestasi::with([$relation, 'tahunAkademik'])
            ->whereHas($relation);

        if ($this->prodiId !== null) {
            $kolomProdi = $this->jenis === 'mahasiswa'
                ? 'mahasiswa.prodi_id'
                : 'dosen.prodi_id';

            $query->whereHas($relation, function ($q) use ($kolomProdi) {
                $q->where($kolomProdi, $this->prodiId);
            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return ['Nama Kegiatan', 'Tingkat', 'Jenis', 'Peringkat', 'Tahun Akademik', 'Atas Nama'];
    }

    public function map($prestasi): array
    {
        $nama = $this->jenis === 'mahasiswa'
            ? $prestasi->mahasiswa->pluck('nama')->implode(', ')
            : $prestasi->dosen->pluck('nama')->implode(', ');

        return [
            $prestasi->nama_kegiatan,
            $prestasi->tingkat ?? '-',
            $prestasi->jenis ?? '-',
            $prestasi->peringkat ?? '-',
            $prestasi->tahunAkademik->label ?? '-',
            $nama ?: '-',
        ];
    }

    public function title(): string
    {
        return $this->jenis === 'mahasiswa' ? 'Prestasi Mahasiswa' : 'Prestasi Dosen';
    }
}
