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
        $angkatanList = Mahasiswa::where('prodi_id', $prodi->id)
            ->whereNotNull('angkatan_masuk')
            ->select('angkatan_masuk')->distinct()
            ->orderByDesc('angkatan_masuk')->pluck('angkatan_masuk');

        $semesterList = Semester::with('tahunAkademik')
            ->orderByDesc('tanggal_mulai')->get();

        $preview = collect();
        $warnings = collect();
        $angkatan = $request->input('angkatan');
        $semesterId = $request->input('semester_id');
        $semesterTujuan = $semesterId ? $semesterList->firstWhere('id', (int) $semesterId) : null;

        if ($request->filled('angkatan') && $semesterTujuan) {
            $mahasiswaList = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
                ->where('prodi_id', $prodi->id)
                ->where('angkatan_masuk', $angkatan)
                ->where('status', 'aktif')
                ->orderBy('nim')->get();

            foreach ($mahasiswaList as $mahasiswa) {
                $kelasAsal = $mahasiswa->kelasMahasiswa;
                $semesterBerikutnya = $mahasiswa->progresStudi?->semester_berikutnya;

                if (! $kelasAsal) {
                    $warnings->push($mahasiswa->nim . ' - ' . $mahasiswa->nama . ': belum memiliki kelas asal.');
                    continue;
                }

                if (! $semesterBerikutnya) {
                    $warnings->push($mahasiswa->nim . ' - ' . $mahasiswa->nama . ': progres semester belum diatur.');
                    continue;
                }

                if ((int) $semesterBerikutnya !== (int) $request->input('semester_tujuan_nomor', $semesterBerikutnya)) {
                    // Nilai semester yang akan diproses tetap ditampilkan dari progres studi.
                }

                $namaTujuan = $kelasAsal->nama_kelas;
                $tingkatAsal = KelasMahasiswa::tingkatDariNama($namaTujuan);
                $semesterAktif = (int) $semesterBerikutnya;

                if ($semesterAktif % 2 === 0) {
                    $namaTujuan = preg_replace('/^\d+/', (string) ($tingkatAsal + 1), $namaTujuan, 1);
                }

                $kelasTujuan = KelasMahasiswa::where('prodi_id', $prodi->id)
                    ->where('nama_kelas', $namaTujuan)
                    ->where('status_kelas', 'aktif')->first();

                $preview->push([
                    'mahasiswa' => $mahasiswa,
                    'kelas_asal' => $kelasAsal,
                    'nama_tujuan' => $namaTujuan,
                    'kelas_tujuan' => $kelasTujuan,
                    'semester_saat_ini' => $semesterAktif,
                    'semester_tujuan' => $semesterAktif + 1,
                ]);

                if (! $kelasTujuan) {
                    $warnings->push($mahasiswa->nim . ' - ' . $mahasiswa->nama . ': kelas tujuan ' . $namaTujuan . ' belum tersedia atau tidak aktif.');
                }
            }
        }

        return view('kaprodi.kenaikan-kelas.index', compact(
            'prodi', 'angkatanList', 'semesterList', 'preview', 'warnings',
            'angkatan', 'semesterTujuan'
        ));
    }
}
