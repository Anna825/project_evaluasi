<?php

namespace App\\Http\\Controllers;

use App\\Models\\Kurikulum;
use App\\Models\\Mahasiswa;
use App\\Services\\CplAggregationService;

class LaporanCplController extends Controller
{
    public function index(Kurikulum $kurikulum, CplAggregationService $service)
    {
        $mahasiswaList = Mahasiswa::with('kelasMahasiswa')
            ->where('prodi_id', $kurikulum->prodi_id)
            ->orderBy('kelas_mahasiswa_id')
            ->orderBy('nama')
            ->get();

        $cplList = $kurikulum->cpl;

        $rekap = $mahasiswaList->map(function ($mhs) use ($kurikulum, $service) {
            return [
                'mahasiswa' => $mhs,
                'capaian' => $service->hitungSemuaCapaianCpl($mhs, $kurikulum->id),
            ];
        });

        $rekapPerKelas = $rekap
            ->groupBy(fn ($item) => $item['mahasiswa']->kelas_mahasiswa_id ?? 'tanpa-kelas')
            ->map(function ($items) {
                return [
                    'kelas' => $items->first()['mahasiswa']->kelasMahasiswa,
                    'rekap' => $items,
                ];
            })
            ->sortBy(fn ($data) => $data['kelas']->nama_kelas ?? 'ZZZ');

        return view(
            'kurikulum.laporan-cpl',
            compact('kurikulum', 'cplList', 'rekapPerKelas')
        );
    }
}
