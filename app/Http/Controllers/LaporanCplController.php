<?php

namespace App\Http\Controllers;

use App\Models\KelasMahasiswa;
use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Services\CplAggregationService;

class LaporanCplController extends Controller
{
    private function pastikanAksesProdi(Kurikulum $kurikulum): void
    {
        $user = auth()->user();
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            return;
        }

        $prodiId = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot?->prodi_id;

        if (! $prodiId || (int) $kurikulum->prodi_id !== (int) $prodiId) {
            abort(403, 'Anda tidak memiliki akses ke laporan CPL Program Studi ini.');
        }
    }

    public function index(Kurikulum $kurikulum)
    {
        $this->pastikanAksesProdi($kurikulum);

        $kelasList = KelasMahasiswa::with('prodi')
            ->withCount('mahasiswa')
            ->where('prodi_id', $kurikulum->prodi_id)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $jumlahMahasiswaTanpaKelas = Mahasiswa::where('prodi_id', $kurikulum->prodi_id)
            ->whereNull('kelas_mahasiswa_id')
            ->count();

        return view(
            'kurikulum.laporan-cpl',
            compact('kurikulum', 'kelasList', 'jumlahMahasiswaTanpaKelas')
        );
    }

    public function showKelas(
        Kurikulum $kurikulum,
        KelasMahasiswa $kelasMahasiswa,
        CplAggregationService $service
    ) {
        $this->pastikanAksesProdi($kurikulum);

        if ((int) $kelasMahasiswa->prodi_id !== (int) $kurikulum->prodi_id) {
            abort(404);
        }

        $kelasMahasiswa->load('prodi');
        $cplList = $kurikulum->cpl;

        $mahasiswaList = Mahasiswa::where('prodi_id', $kurikulum->prodi_id)
            ->where('kelas_mahasiswa_id', $kelasMahasiswa->id)
            ->orderBy('nama')
            ->get();

        $rekap = $mahasiswaList->map(function ($mhs) use ($kurikulum, $service) {
            return [
                'mahasiswa' => $mhs,
                'capaian' => $service->hitungSemuaCapaianCpl($mhs, $kurikulum->id),
            ];
        });

        return view(
            'kurikulum.laporan-cpl-kelas',
            compact('kurikulum', 'kelasMahasiswa', 'cplList', 'rekap')
        );
    }
}
