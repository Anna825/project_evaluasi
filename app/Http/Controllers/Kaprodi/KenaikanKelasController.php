<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Illuminate\Http\Request;

class KenaikanKelasController extends Controller
{
    private function prodiKaprodi()
    {
        $role = auth()->user()->userRoles()
            ->whereHas('role', fn ($query) => $query->where('nama_role', 'kaprodi'))
            ->with('prodi')
            ->first();

        if (! $role || ! $role->prodi_id) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        return $role->prodi;
    }

    public function index(Request $request)
    {
        $prodi = $this->prodiKaprodi();
        $kelasList = KelasMahasiswa::where('prodi_id', $prodi->id)
            ->where('status_kelas', 'aktif')
            ->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $semesterList = Semester::with('tahunAkademik')->orderByDesc('tanggal_mulai')->get();
        $preview = collect();
        $warnings = collect();
        $kelasAsalId = $request->input('kelas_asal_id');
        $semesterId = $request->input('semester_id');
        $kelasAsalTerpilih = $kelasAsalId ? $kelasList->firstWhere('id', (int) $kelasAsalId) : null;
        $semesterTujuan = $semesterId ? $semesterList->firstWhere('id', (int) $semesterId) : null;

        if ($request->filled('kelas_asal_id') && ! $kelasAsalTerpilih) {
            abort(404, 'Kelas asal tidak ditemukan atau tidak aktif.');
        }

        if ($kelasAsalTerpilih && $semesterTujuan) {
            $mahasiswaList = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
                ->where('prodi_id', $prodi->id)
                ->where('kelas_mahasiswa_id', $kelasAsalTerpilih->id)
                ->where('status', 'aktif')->orderBy('nim')->get();

            foreach ($mahasiswaList as $mahasiswa) {
                $semesterBerikutnya = $mahasiswa->progresStudi?->semester_berikutnya;
                if (! $semesterBerikutnya) {
                    $warnings->push([
                        'nim' => $mahasiswa->nim, 'nama' => $mahasiswa->nama,
                        'alasan' => 'Progres semester belum tersedia. Perlu dihitung dari kalender akademik dan riwayat cuti.',
                    ]);
                    continue;
                }

                $semesterSaatIni = (int) $semesterBerikutnya;
                $tingkatAsal = KelasMahasiswa::tingkatDariNama($kelasAsalTerpilih->nama_kelas);
                $namaTujuan = $kelasAsalTerpilih->nama_kelas;
                if ($semesterSaatIni % 2 === 0) {
                    $namaTujuan = preg_replace('/^\\d+/', (string) ($tingkatAsal + 1), $namaTujuan, 1);
                }
                $kelasTujuan = KelasMahasiswa::where('prodi_id', $prodi->id)
                    ->where('nama_kelas', $namaTujuan)->where('status_kelas', 'aktif')->first();
                $preview->push([
                    'mahasiswa' => $mahasiswa, 'kelas_asal' => $kelasAsalTerpilih,
                    'nama_tujuan' => $namaTujuan, 'kelas_tujuan' => $kelasTujuan,
                    'semester_saat_ini' => $semesterSaatIni, 'semester_tujuan' => $semesterSaatIni + 1,
                ]);
                if (! $kelasTujuan) {
                    $warnings->push([
                        'nim' => $mahasiswa->nim, 'nama' => $mahasiswa->nama,
                        'alasan' => 'Kelas tujuan ' . $namaTujuan . ' belum tersedia atau tidak aktif.',
                    ]);
                }
            }
        }

        return view('kaprodi.kenaikan-kelas.index', compact(
            'prodi', 'kelasList', 'semesterList', 'preview', 'warnings',
            'kelasAsalTerpilih', 'semesterTujuan'
        ));
    }
}
