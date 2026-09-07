<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\PenelitianPkm;
use App\Models\Prestasi;
use App\Services\CplAggregationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman utama pusat laporan.
     */
    public function index()
    {
        return view('report.index');
    }

    /**
     * Export data Mahasiswa ke Excel.
     */
    public function mahasiswaExcel()
    {
        return Excel::download(new \App\Exports\MahasiswaExport(), 'data-mahasiswa.xlsx');
    }

    /**
     * Export rekap Prestasi ke Excel.
     */
    public function prestasiExcel()
    {
        return Excel::download(new \App\Exports\PrestasiExport(), 'rekap-prestasi.xlsx');
    }

    /**
     * Export rekap Penelitian/PKM ke Excel.
     */
    public function penelitianExcel()
    {
        return Excel::download(new \App\Exports\PenelitianPkmExport(), 'rekap-penelitian-pkm.xlsx');
    }

    /**
     * Export Laporan Capaian CPL ke PDF.
     */
    public function laporanCplPdf(Kurikulum $kurikulum, CplAggregationService $service)
    {
        $mahasiswaList = Mahasiswa::where('prodi_id', $kurikulum->prodi_id)->orderBy('nama')->get();
        $cplList = $kurikulum->cpl;

        $rekap = $mahasiswaList->map(function ($mhs) use ($kurikulum, $service) {
            return [
                'mahasiswa' => $mhs,
                'capaian' => $service->hitungSemuaCapaianCpl($mhs, $kurikulum->id),
            ];
        });

        $pdf = Pdf::loadView('report.laporan-cpl-pdf', compact('kurikulum', 'cplList', 'rekap'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-capaian-cpl-' . str_replace(' ', '-', strtolower($kurikulum->nama)) . '.pdf');
    }
}