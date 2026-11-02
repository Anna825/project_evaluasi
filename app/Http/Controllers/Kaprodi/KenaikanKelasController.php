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
    public function store(Request $request)
    {
        $prodi = $this->prodiKaprodi();
        $data = $request->validate([
            'kelas_asal_id' => ['required', 'integer', 'exists:kelas_mahasiswa,id'],
            'semester_id' => ['required', 'integer', 'exists:semester,id'],
        ]);

        $kelasAsal = KelasMahasiswa::where('id', $data['kelas_asal_id'])
            ->where('prodi_id', $prodi->id)->where('status_kelas', 'aktif')->firstOrFail();
        $semesterTujuan = Semester::findOrFail($data['semester_id']);

        if ($semesterTujuan->jenis !== 'ganjil') {
            throw ValidationException::withMessages([
                'semester_id' => 'Kenaikan tingkat kelas hanya dapat dilakukan menuju semester Ganjil setelah semester Genap selesai.',
            ]);
        }

        $semesterGenapSebelumnya = Semester::where('tanggal_selesai', '<', $semesterTujuan->tanggal_mulai)
            ->where('jenis', 'genap')->orderByDesc('tanggal_selesai')->first();

        if (! $semesterGenapSebelumnya || today()->lt($semesterGenapSebelumnya->tanggal_selesai)) {
            throw ValidationException::withMessages([
                'semester_id' => 'Semester Genap sebelumnya belum selesai menurut kalender akademik.',
            ]);
        }

        DB::transaction(function () use ($data, $prodi, $kelasAsal, $semesterTujuan) {
            if (DB::table('riwayat_batch_kenaikan_kelas')
                ->where('kelas_asal_id', $kelasAsal->id)
                ->where('semester_tujuan_id', $semesterTujuan->id)->exists()) {
                throw ValidationException::withMessages([
                    'kelas_asal_id' => 'Kenaikan untuk kelas dan periode ini sudah pernah diproses.',
                ]);
            }

            $mahasiswaList = Mahasiswa::with('progresStudi')
                ->where('prodi_id', $prodi->id)
                ->where('kelas_mahasiswa_id', $kelasAsal->id)
                ->where('status', 'aktif')->lockForUpdate()->get();

            if ($mahasiswaList->isEmpty()) {
                throw ValidationException::withMessages([
                    'kelas_asal_id' => 'Tidak ada mahasiswa Aktif pada kelas ini.',
                ]);
            }

            $rencana = [];
            foreach ($mahasiswaList as $mahasiswa) {
                $semesterBerikutnya = $mahasiswa->progresStudi?->semester_berikutnya;
                if (! $semesterBerikutnya) {
                    throw ValidationException::withMessages([
                        'kelas_asal_id' => 'Progres semester mahasiswa ' . $mahasiswa->nim . ' belum tersedia. Proses dibatalkan tanpa mengubah data.',
                    ]);
                }

                $tingkatAsal = KelasMahasiswa::tingkatDariNama($kelasAsal->nama_kelas);
                $namaTujuan = $kelasAsal->nama_kelas;
                if ((int) $semesterBerikutnya % 2 === 0) {
                    $namaTujuan = preg_replace('/^\\d+/', (string) ($tingkatAsal + 1), $namaTujuan, 1);
                }

                $kelasTujuan = KelasMahasiswa::where('prodi_id', $prodi->id)
                    ->where('nama_kelas', $namaTujuan)->where('status_kelas', 'aktif')->first();

                if (! $kelasTujuan) {
                    throw ValidationException::withMessages([
                        'kelas_asal_id' => 'Kelas tujuan ' . $namaTujuan . ' untuk mahasiswa ' . $mahasiswa->nim . ' belum tersedia. Tidak ada perubahan yang dilakukan.',
                    ]);
                }

                $rencana[] = compact('mahasiswa', 'kelasTujuan', 'semesterBerikutnya');
            }

            DB::table('riwayat_batch_kenaikan_kelas')->insert([
                'prodi_id' => $prodi->id,
                'kelas_asal_id' => $kelasAsal->id,
                'semester_tujuan_id' => $semesterTujuan->id,
                'status' => 'selesai',
                'jumlah_mahasiswa' => count($rencana),
                'dilakukan_oleh' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($rencana as $item) {
                $mahasiswa = $item['mahasiswa'];
                $kelasTujuan = $item['kelasTujuan'];
                $progres = $mahasiswa->progresStudi;
                $before = [
                    'kelas_mahasiswa_id' => $mahasiswa->kelas_mahasiswa_id,
                    'semester_berikutnya' => $progres->semester_berikutnya,
                ];

                $mahasiswa->update(['kelas_mahasiswa_id' => $kelasTujuan->id]);
                $progres->update([
                    'semester_terakhir_selesai' => max((int) $progres->semester_terakhir_selesai, (int) $progres->semester_berikutnya),
                    'semester_berikutnya' => (int) $progres->semester_berikutnya + 1,
                    'semester_terakhir_id' => $semesterGenapSebelumnya->id,
                    'status_progres' => 'berjalan',
                ]);

                RiwayatPenempatanKelas::updateOrCreate(
                    ['mahasiswa_id' => $mahasiswa->id, 'semester_id' => $semesterTujuan->id],
                    ['kelas_mahasiswa_id' => $kelasTujuan->id, 'alasan_penempatan' => 'kenaikan_kelas', 'dilakukan_oleh' => auth()->id()]
                );

                RiwayatAksiAkademik::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'jenis_aksi' => 'kenaikan_kelas',
                    'data_sebelum' => $before,
                    'data_sesudah' => [
                        'kelas_mahasiswa_id' => $kelasTujuan->id,
                        'semester_berikutnya' => $progres->semester_berikutnya,
                    ],
                    'alasan' => 'Kenaikan kelas terkonfirmasi untuk periode ' . ($semesterTujuan->tahunAkademik?->label ?? $semesterTujuan->id) . ' Ganjil.',
                    'dilakukan_oleh' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('kaprodi.kenaikan-kelas.index')
            ->with('status', 'Kenaikan kelas berhasil diproses untuk seluruh mahasiswa Aktif pada kelas terpilih.');
    }

}
