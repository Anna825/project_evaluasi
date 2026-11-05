<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\RiwayatAksiAkademik;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

    private function rencanaNamaKelas(KelasMahasiswa $kelas): array
    {
        $tingkatAsal = KelasMahasiswa::tingkatDariNama($kelas->nama_kelas);
        $namaTujuan = preg_replace('/^\d+/', (string) ($tingkatAsal + 1), $kelas->nama_kelas, 1);

        return [$tingkatAsal, $namaTujuan];
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
            $semesterGenapSebelumnya = Semester::where('jenis', 'genap')
                ->where('tanggal_selesai', '<', $semesterTujuan->tanggal_mulai)
                ->orderByDesc('tanggal_selesai')
                ->first();

            if ($semesterTujuan->jenis !== 'ganjil') {
                $warnings->push([
                    'nim' => '-', 'nama' => 'Validasi periode',
                    'alasan' => 'Kenaikan tingkat hanya dapat ditujukan ke Semester Ganjil.',
                ]);
            } elseif (! $semesterGenapSebelumnya) {
                $warnings->push([
                    'nim' => '-', 'nama' => 'Validasi kalender akademik',
                    'alasan' => 'Semester Genap sebelumnya tidak ditemukan pada kalender akademik.',
                ]);
            } elseif (today()->lt($semesterGenapSebelumnya->tanggal_selesai)) {
                $warnings->push([
                    'nim' => '-', 'nama' => 'Validasi kalender akademik',
                    'alasan' => 'Semester Genap sebelumnya belum melewati tanggal akhir.',
                ]);
            } else {
                [$tingkatAsal, $namaTujuan] = $this->rencanaNamaKelas($kelasAsalTerpilih);

                $bentrokKelas = KelasMahasiswa::where('prodi_id', $prodi->id)
                    ->where('nama_kelas', $namaTujuan)
                    ->where('angkatan', $kelasAsalTerpilih->angkatan)
                    ->where('id', '!=', $kelasAsalTerpilih->id)
                    ->where('status_kelas', 'aktif')
                    ->exists();

                if ($bentrokKelas) {
                    $warnings->push([
                        'nim' => '-', 'nama' => 'Validasi identitas kelas',
                        'alasan' => 'Nama kelas tujuan ' . $namaTujuan . ' sudah digunakan pada angkatan ' . $kelasAsalTerpilih->angkatan . '. Periksa data kelas agar tidak terjadi penggabungan.',
                    ]);
                }

                $mahasiswaList = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
                    ->where('prodi_id', $prodi->id)
                    ->where('kelas_mahasiswa_id', $kelasAsalTerpilih->id)
                    ->where('status', 'aktif')->orderBy('nim')->get();

                $sudahDiproses = DB::table('riwayat_batch_kenaikan_kelas')
                    ->where('kelas_asal_id', $kelasAsalTerpilih->id)
                    ->where('semester_tujuan_id', $semesterTujuan->id)
                    ->exists();

                if ($sudahDiproses) {
                    $warnings->push([
                        'nim' => '-', 'nama' => 'Validasi duplikasi',
                        'alasan' => 'Kenaikan kelas untuk kelas dan periode ini sudah pernah diproses.',
                    ]);
                }

                foreach ($mahasiswaList as $mahasiswa) {
                    $detailSelesai = DB::table('riwayat_detail_penyelesaian_semester')
                        ->where('mahasiswa_id', $mahasiswa->id)
                        ->where('semester_id', $semesterGenapSebelumnya->id)
                        ->first();

                    $semesterSelesai = (int) ($detailSelesai->semester_diselesaikan ?? 0);
                    $semesterBerikutnya = (int) ($detailSelesai->semester_berikutnya ?? 0);
                    $alasan = null;

                    if (! $detailSelesai) {
                        $alasan = 'Belum tercatat menyelesaikan Semester Genap sebelumnya (' . ($semesterGenapSebelumnya->tahunAkademik->label ?? 'periode terkait') . ').';
                    } elseif ($semesterSelesai < 1 || $semesterSelesai % 2 !== 0 || $semesterBerikutnya !== $semesterSelesai + 1) {
                        $alasan = 'Catatan progres tidak konsisten atau semester terakhir yang diselesaikan bukan semester genap.';
                    } elseif ((int) ($mahasiswa->progresStudi?->semester_terakhir_selesai ?? 0) !== $semesterSelesai
                        || (int) ($mahasiswa->progresStudi?->semester_berikutnya ?? 0) !== $semesterBerikutnya) {
                        $alasan = 'Data progres studi tidak sama dengan riwayat penyelesaian semester.';
                    }

                    $preview->push([
                        'mahasiswa' => $mahasiswa,
                        'semester_selesai' => $semesterSelesai,
                        'semester_tujuan' => $semesterBerikutnya,
                        'nama_tujuan' => $namaTujuan,
                        'layak' => ! $alasan && ! $bentrokKelas && ! $sudahDiproses,
                        'alasan' => $alasan,
                    ]);

                    if ($alasan) {
                        $warnings->push([
                            'nim' => $mahasiswa->nim,
                            'nama' => $mahasiswa->nama,
                            'alasan' => $alasan,
                        ]);
                    }
                }

                if ($mahasiswaList->isEmpty()) {
                    $warnings->push([
                        'nim' => '-', 'nama' => 'Validasi kelas',
                        'alasan' => 'Tidak ada mahasiswa Aktif pada kelas ini.',
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

        DB::transaction(function () use ($data, $prodi) {
            $kelas = KelasMahasiswa::where('id', $data['kelas_asal_id'])
                ->where('prodi_id', $prodi->id)
                ->where('status_kelas', 'aktif')
                ->lockForUpdate()
                ->firstOrFail();
            $semesterTujuan = Semester::with('tahunAkademik')->findOrFail($data['semester_id']);

            if ($semesterTujuan->jenis !== 'ganjil') {
                throw ValidationException::withMessages([
                    'semester_id' => 'Kenaikan tingkat hanya dapat dilakukan menuju Semester Ganjil.',
                ]);
            }

            $semesterGenapSebelumnya = Semester::where('jenis', 'genap')
                ->where('tanggal_selesai', '<', $semesterTujuan->tanggal_mulai)
                ->orderByDesc('tanggal_selesai')
                ->first();

            if (! $semesterGenapSebelumnya || today()->lt($semesterGenapSebelumnya->tanggal_selesai)) {
                throw ValidationException::withMessages([
                    'semester_id' => 'Semester Genap sebelumnya belum selesai menurut kalender akademik.',
                ]);
            }

            if (DB::table('riwayat_batch_kenaikan_kelas')
                ->where('kelas_asal_id', $kelas->id)
                ->where('semester_tujuan_id', $semesterTujuan->id)
                ->exists()) {
                throw ValidationException::withMessages([
                    'kelas_asal_id' => 'Kenaikan untuk kelas dan periode ini sudah pernah diproses.',
                ]);
            }

            [$tingkatAsal, $namaTujuan] = $this->rencanaNamaKelas($kelas);

            $bentrokKelas = KelasMahasiswa::where('prodi_id', $prodi->id)
                ->where('nama_kelas', $namaTujuan)
                ->where('angkatan', $kelas->angkatan)
                ->where('id', '!=', $kelas->id)
                ->where('status_kelas', 'aktif')
                ->exists();

            if ($bentrokKelas) {
                throw ValidationException::withMessages([
                    'kelas_asal_id' => 'Nama kelas tujuan ' . $namaTujuan . ' sudah digunakan pada angkatan yang sama. Proses dibatalkan.',
                ]);
            }

            $mahasiswaList = Mahasiswa::with('progresStudi')
                ->where('prodi_id', $prodi->id)
                ->where('kelas_mahasiswa_id', $kelas->id)
                ->where('status', 'aktif')
                ->orderBy('nim')
                ->lockForUpdate()
                ->get();

            if ($mahasiswaList->isEmpty()) {
                throw ValidationException::withMessages([
                    'kelas_asal_id' => 'Tidak ada mahasiswa Aktif pada kelas ini.',
                ]);
            }

            $rencana = [];
            foreach ($mahasiswaList as $mahasiswa) {
                $detail = DB::table('riwayat_detail_penyelesaian_semester')
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->where('semester_id', $semesterGenapSebelumnya->id)
                    ->first();

                if (! $detail) {
                    throw ValidationException::withMessages([
                        'kelas_asal_id' => 'Mahasiswa ' . $mahasiswa->nim . ' belum menyelesaikan Semester Genap sebelumnya. Tidak ada perubahan yang dilakukan.',
                    ]);
                }

                $selesai = (int) $detail->semester_diselesaikan;
                $berikutnya = (int) $detail->semester_berikutnya;

                if ($selesai < 1 || $selesai % 2 !== 0 || $berikutnya !== $selesai + 1
                    || (int) ($mahasiswa->progresStudi?->semester_terakhir_selesai ?? 0) !== $selesai
                    || (int) ($mahasiswa->progresStudi?->semester_berikutnya ?? 0) !== $berikutnya) {
                    throw ValidationException::withMessages([
                        'kelas_asal_id' => 'Progres mahasiswa ' . $mahasiswa->nim . ' tidak konsisten. Seluruh proses dibatalkan tanpa mengubah data.',
                    ]);
                }

                $rencana[] = $mahasiswa;
            }

            // Buat identitas kelas tujuan baru. Pertahankan record kelas asal agar mahasiswa Cuti
            // tetap memakai ID kelas asal sampai mengikuti prosedur penempatan saat kembali aktif.
            $kelasAsalId = $kelas->id;
            $kelasSebelum = [
                'kelas_mahasiswa_id' => $kelasAsalId,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
                'angkatan' => $kelas->angkatan,
            ];

            $kelasTujuan = KelasMahasiswa::create([
                'prodi_id' => $kelas->prodi_id,
                'nama_kelas' => $namaTujuan,
                'tingkat' => $tingkatAsal + 1,
                'angkatan' => $kelas->angkatan,
                'status_kelas' => 'aktif',
            ]);

            DB::table('riwayat_batch_kenaikan_kelas')->insert([
                'prodi_id' => $prodi->id,
                'kelas_asal_id' => $kelas->id,
                'semester_tujuan_id' => $semesterTujuan->id,
                'status' => 'selesai',
                'jumlah_mahasiswa' => count($rencana),
                'dilakukan_oleh' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($rencana as $mahasiswa) {
                $mahasiswa->update([
                    'kelas_mahasiswa_id' => $kelasTujuan->id,
                ]);

                RiwayatAksiAkademik::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'jenis_aksi' => 'kenaikan_kelas',
                    'data_sebelum' => [
                        'kelas_mahasiswa_id' => $kelas->id,
                        'nama_kelas' => $kelasSebelum['nama_kelas'],
                        'tingkat' => $kelasSebelum['tingkat'],
                        'angkatan' => $kelasSebelum['angkatan'],
                    ],
                    'data_sesudah' => [
                        'kelas_mahasiswa_id' => $kelasTujuan->id,
                        'nama_kelas' => $namaTujuan,
                        'tingkat' => $tingkatAsal + 1,
                        'angkatan' => $kelas->angkatan,
                    ],
                    'alasan' => 'Kenaikan kelas dikonfirmasi Kaprodi untuk periode ' . ($semesterTujuan->tahunAkademik?->label ?? $semesterTujuan->id) . ' Ganjil.',
                    'dilakukan_oleh' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('kaprodi.kenaikan-kelas.index')
            ->with('status', 'Kenaikan kelas berhasil. Identitas kelas mahasiswa Aktif diperbarui; mahasiswa Cuti tetap menggunakan kelas asal.');
    }
}
