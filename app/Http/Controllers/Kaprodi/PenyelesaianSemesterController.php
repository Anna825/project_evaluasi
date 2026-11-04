<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\ProgresStudiMahasiswa;
use App\Models\RiwayatAksiAkademik;
use App\Models\Semester;
use App\Services\AcademicProgressCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenyelesaianSemesterController extends Controller
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

    private function tingkatKelasMahasiswa(?KelasMahasiswa $kelas): ?int
    {
        if (! $kelas) {
            return null;
        }

        $tingkat = (int) ($kelas->tingkat ?? 0);

        return $tingkat > 0 ? $tingkat : KelasMahasiswa::tingkatDariNama($kelas->nama_kelas);
    }

    private function validasiTingkatUntukSemester(Mahasiswa $mahasiswa, ?int $nomorSemester): ?string
    {
        if (! $nomorSemester) {
            return null;
        }

        $tingkatKelas = $this->tingkatKelasMahasiswa($mahasiswa->kelasMahasiswa);
        $tingkatSeharusnya = intdiv($nomorSemester + 1, 2);

        if (! $tingkatKelas) {
            return 'Mahasiswa belum memiliki kelas aktif yang dapat divalidasi.';
        }

        if ($tingkatKelas !== $tingkatSeharusnya) {
            return 'Semester ' . $nomorSemester . ' membutuhkan kelas tingkat ' . $tingkatSeharusnya
                . ', sedangkan mahasiswa masih tercatat di tingkat ' . $tingkatKelas
                . '. Proses kenaikan kelas harus dilakukan terlebih dahulu.';
        }

        return null;
    }

    public function index(Request $request)
    {
        $prodi = $this->prodiKaprodi();
        $semesterList = Semester::with('tahunAkademik')->orderByDesc('tanggal_mulai')->get();
        $kelasList = KelasMahasiswa::where('prodi_id', $prodi->id)
            ->where('status_kelas', 'aktif')->orderBy('tingkat')->orderBy('nama_kelas')->get();

        $semester = $request->filled('semester_id')
            ? $semesterList->firstWhere('id', (int) $request->semester_id)
            : null;
        if ($request->filled('semester_id') && ! $semester) {
            abort(404, 'Semester tidak ditemukan.');
        }

        $kelasId = $request->input('kelas_id');
        $kelasTerpilih = $kelasId ? $kelasList->firstWhere('id', (int) $kelasId) : null;
        if ($kelasId && ! $kelasTerpilih) {
            abort(404, 'Kelas tidak ditemukan atau bukan milik Program Studi Anda.');
        }

        $mahasiswaList = collect();
        $semesterSelesai = $semester && today()->gte($semester->tanggal_selesai);

        if ($semester && $semesterSelesai) {
            $mahasiswaList = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
                ->where('prodi_id', $prodi->id)
                ->where('status', 'aktif')
                ->when($kelasTerpilih, fn ($query) => $query->where('kelas_mahasiswa_id', $kelasTerpilih->id))
                ->orderBy('nim')->get();

            $sudahDiproses = DB::table('riwayat_detail_penyelesaian_semester')
                ->where('semester_id', $semester->id)
                ->pluck('mahasiswa_id')->map(fn ($id) => (int) $id)->all();

            $calculator = app(AcademicProgressCalculator::class);
            $mahasiswaList->each(function ($mahasiswa) use ($semester, $sudahDiproses, $calculator) {
                $nomorSemester = $mahasiswa->progresStudi?->semester_berikutnya
                    ?: $calculator->semesterBerjalan($mahasiswa, $semester);
                $mahasiswa->nomor_semester_acuan = $nomorSemester;
                $mahasiswa->sudah_diselesaikan = in_array((int) $mahasiswa->id, $sudahDiproses, true);
                $progresTerakhir = (int) ($mahasiswa->progresStudi?->semester_terakhir_selesai ?? 0);
                $mahasiswa->alasan_validasi = null;

                if ($mahasiswa->sudah_diselesaikan) {
                    $mahasiswa->alasan_validasi = 'Semester ini sudah pernah diselesaikan.';
                } elseif (! $nomorSemester) {
                    $mahasiswa->alasan_validasi = 'Semester studi belum dapat dihitung. Periksa angkatan masuk dan riwayat cuti.';
                } elseif ($progresTerakhir >= $nomorSemester) {
                    $mahasiswa->alasan_validasi = 'Progres terakhir tercatat sudah mencapai semester ini atau lebih tinggi.';
                } else {
                    $mahasiswa->alasan_validasi = $this->validasiTingkatUntukSemester($mahasiswa, $nomorSemester);
                }
                $mahasiswa->layak_diselesaikan = ! $mahasiswa->alasan_validasi;
            });
        }

        $jumlahLayak = $mahasiswaList->where('layak_diselesaikan', true)->count();
        $jumlahPerluDitinjau = $mahasiswaList->count() - $jumlahLayak;

        return view('kaprodi.penyelesaian-semester.index', compact(
            'prodi', 'semesterList', 'kelasList', 'semester', 'kelasTerpilih',
            'mahasiswaList', 'jumlahLayak', 'jumlahPerluDitinjau', 'semesterSelesai'
        ));
    }

    public function store(Request $request)
    {
        $prodi = $this->prodiKaprodi();
        $data = $request->validate([
            'semester_id' => ['required', 'integer', 'exists:semester,id'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas_mahasiswa,id'],
            'mahasiswa_ids' => ['required', 'array', 'min:1'],
            'mahasiswa_ids.*' => ['required', 'integer', 'distinct', 'exists:mahasiswa,id'],
        ]);

        $semester = Semester::findOrFail($data['semester_id']);
        if (today()->lt($semester->tanggal_selesai)) {
            throw ValidationException::withMessages([
                'semester_id' => 'Tanggal akhir semester belum terlewati.',
            ]);
        }

        $kelas = null;
        if (! empty($data['kelas_id'])) {
            $kelas = KelasMahasiswa::where('id', $data['kelas_id'])
                ->where('prodi_id', $prodi->id)->where('status_kelas', 'aktif')->firstOrFail();
        }

        DB::transaction(function () use ($data, $prodi, $semester, $kelas) {
            $mahasiswaList = Mahasiswa::with(['progresStudi', 'kelasMahasiswa'])
                ->where('prodi_id', $prodi->id)
                ->where('status', 'aktif')
                ->whereIn('id', $data['mahasiswa_ids'])
                ->when($kelas, fn ($query) => $query->where('kelas_mahasiswa_id', $kelas->id))
                ->lockForUpdate()->get();

            if ($mahasiswaList->count() !== count($data['mahasiswa_ids'])) {
                throw ValidationException::withMessages([
                    'mahasiswa_ids' => 'Ada mahasiswa yang bukan bagian dari Program Studi/kelas Anda atau tidak berstatus Aktif.',
                ]);
            }

            $calculator = app(AcademicProgressCalculator::class);
            $rencana = [];
            foreach ($mahasiswaList as $mahasiswa) {
                $nomorSemester = $mahasiswa->progresStudi?->semester_berikutnya
                    ?: $calculator->semesterBerjalan($mahasiswa, $semester);
                $progresTerakhir = (int) ($mahasiswa->progresStudi?->semester_terakhir_selesai ?? 0);
                $sudahDiproses = DB::table('riwayat_detail_penyelesaian_semester')
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->where('semester_id', $semester->id)->exists();

                if ($sudahDiproses) {
                    throw ValidationException::withMessages([
                        'mahasiswa_ids' => 'Mahasiswa ' . $mahasiswa->nim . ' sudah diproses untuk semester ini.',
                    ]);
                }
                if (! $nomorSemester || $progresTerakhir >= $nomorSemester) {
                    throw ValidationException::withMessages([
                        'mahasiswa_ids' => 'Progres mahasiswa ' . $mahasiswa->nim . ' tidak valid atau belum dapat dihitung. Tidak ada data yang diubah.',
                    ]);
                }

                $alasanTingkat = $this->validasiTingkatUntukSemester($mahasiswa, (int) $nomorSemester);
                if ($alasanTingkat) {
                    throw ValidationException::withMessages([
                        'mahasiswa_ids' => 'Mahasiswa ' . $mahasiswa->nim . ': ' . $alasanTingkat . ' Tidak ada data yang diubah.',
                    ]);
                }

                $rencana[] = ['mahasiswa' => $mahasiswa, 'nomor_semester' => $nomorSemester];
            }

            $batchId = DB::table('riwayat_batch_penyelesaian_semester')->insertGetId([
                'prodi_id' => $prodi->id,
                'semester_id' => $semester->id,
                'jumlah_mahasiswa' => count($rencana),
                'dikonfirmasi_oleh' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($rencana as $item) {
                $mahasiswa = $item['mahasiswa'];
                $nomorSemester = (int) $item['nomor_semester'];
                $progres = $mahasiswa->progresStudi;
                $before = [
                    'semester_terakhir_selesai' => $progres?->semester_terakhir_selesai,
                    'semester_berikutnya' => $progres?->semester_berikutnya,
                    'semester_terakhir_id' => $progres?->semester_terakhir_id,
                ];
                $after = [
                    'semester_terakhir_selesai' => $nomorSemester,
                    'semester_berikutnya' => $nomorSemester + 1,
                    'semester_terakhir_id' => $semester->id,
                ];

                ProgresStudiMahasiswa::updateOrCreate(
                    ['mahasiswa_id' => $mahasiswa->id],
                    $after + ['status_progres' => 'berjalan']
                );

                DB::table('riwayat_detail_penyelesaian_semester')->insert([
                    'batch_id' => $batchId,
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester_id' => $semester->id,
                    'semester_diselesaikan' => $nomorSemester,
                    'semester_berikutnya' => $nomorSemester + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                RiwayatAksiAkademik::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'jenis_aksi' => 'penyelesaian_semester',
                    'data_sebelum' => $before,
                    'data_sesudah' => $after,
                    'alasan' => 'Penyelesaian ' . ucfirst($semester->jenis) . ' dikonfirmasi Kaprodi.',
                    'dilakukan_oleh' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('kaprodi.penyelesaian-semester.index', [
            'semester_id' => $semester->id,
            'kelas_id' => $data['kelas_id'] ?? null,
        ])->with('status', 'Penyelesaian semester berhasil dicatat untuk mahasiswa yang dipilih.');
    }
}
