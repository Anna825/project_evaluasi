<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Services\CplAggregationService;

class LaporanCplController extends Controller
{
    public function index(Kurikulum $kurikulum, CplAggregationService $service)
    {
        $mahasiswaList = Mahasiswa::where('prodi_id', $kurikulum->prodi_id)->orderBy('nama')->get();
        $cplList = $kurikulum->cpl;

        $rekap = $mahasiswaList->map(function ($mhs) use ($kurikulum, $service) {
            return [
                'mahasiswa' => $mhs,
                'capaian' => $service->hitungSemuaCapaianCpl($mhs, $kurikulum->id),
            ];
        });

        return view('kurikulum.laporan-cpl', compact('kurikulum', 'cplList', 'rekap'));
    }
}