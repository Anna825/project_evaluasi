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
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            $kurikulumList = Kurikulum::with('prodi')
                ->latest()
                ->get();
        } else {
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

    public function mahasiswaExcel()
    {
        return Excel::download(new \App\Exports\MahasiswaExport(), 'data-mahasiswa.xlsx');
    }

    public function prestasiExcel()
    {
        return Excel::download(new \App\Exports\PrestasiExport(), 'rekap-prestasi.xlsx');
    }

    public function penelitianExcel()
    {
        return Excel::download(new \App\Exports\PenelitianPkmExport(), 'rekap-penelitian-pkm.xlsx');
    }

    public function laporanCplPdf(
        Kurikulum $kurikulum,
        CplAggregationService $service
    ) {
        $user = auth()->user();
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if (! $isAdmin) {
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;
            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            if ((int) $kurikulum->prodi_id !== (int) $prodiId) {
                abort(
                    403,
                    'Anda tidak memiliki akses ke laporan CPL Program Studi lain.'
                );
            }
        }

        $mahasiswaList = Mahasiswa::with('kelasMahasiswa')
            ->where('prodi_id', $kurikulum->prodi_id)
            ->orderBy('kelas_mahasiswa_id')
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

        $rekapPerKelas = $rekap
            ->groupBy(fn ($item) => $item['mahasiswa']->kelas_mahasiswa_id ?? 0)
            ->map(function ($items) {
                return [
                    'kelas' => $items->first()['mahasiswa']->kelasMahasiswa,
                    'rekap' => $items,
                ];
            });

        $pdf = Pdf::loadView(
            'report.laporan-cpl-pdf',
            compact(
                'kurikulum',
                'cplList',
                'rekapPerKelas'
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