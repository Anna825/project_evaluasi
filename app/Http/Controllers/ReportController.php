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
        $user = auth()->user();

        // Admin dapat melihat semua kurikulum.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            $kurikulumList = Kurikulum::with('prodi')
                ->latest()
                ->get();
        } else {
            // Kaprodi hanya melihat kurikulum dari Prodi sendiri.
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $kurikulumList = Kurikulum::with('prodi')
                ->where('prodi_id', $prodiId)
                ->latest()
                ->get();
        }

        return view('report.index', compact('kurikulumList'));
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
    public function laporanCplPdf(
        Kurikulum $kurikulum,
        CplAggregationService $service
    ) {
        $user = auth()->user();

        // Admin boleh mengakses laporan semua Prodi.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if (! $isAdmin) {
            // Ambil Prodi dari role Kaprodi.
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            // Dinding pemisah antar Prodi.
            if ((int) $kurikulum->prodi_id !== (int) $prodiId) {
                abort(
                    403,
                    'Anda tidak memiliki akses ke laporan CPL Program Studi lain.'
                );
            }
        }

        $mahasiswaList = Mahasiswa::where(
            'prodi_id',
            $kurikulum->prodi_id
        )
            ->orderBy('nama')
            ->get();

        $cplList = $kurikulum->cpl;

        $rekap = $mahasiswaList->map(function ($mhs) use (
            $kurikulum,
            $service
        ) {
            return [
                'mahasiswa' => $mhs,
                'capaian' => $service->hitungSemuaCapaianCpl(
                    $mhs,
                    $kurikulum->id
                ),
            ];
        });

        $pdf = Pdf::loadView(
            'report.laporan-cpl-pdf',
            compact(
                'kurikulum',
                'cplList',
                'rekap'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-capaian-cpl-' .
            str_replace(
                ' ',
                '-',
                strtolower($kurikulum->nama)
            ) .
            '.pdf'
        );
    }
}