<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MahasiswaStatusLog;
use App\Models\ProgresStudiMahasiswa;
use App\Models\RiwayatPenempatanKelas;
use App\Models\RiwayatCutiMahasiswa;
use App\Models\RiwayatAksiAkademik;
use App\Models\Semester;
use App\Services\AcademicProgressCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenempatanMahasiswaController extends Controller
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
        // Halaman ini khusus untuk mahasiswa yang masih berstatus Cuti.
        // Mahasiswa Aktif tidak perlu muncul karena tidak membutuhkan penempatan kembali.
        $query = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
            ->where('prodi_id', $prodi->id)
            ->where('status', 'cuti');

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(fn ($q) => $q
                ->where('nim', 'like', "%{$keyword}%")
                ->orWhere('nama', 'like', "%{$keyword}%"));
        }

        $mahasiswa = $query->orderBy('nim')->paginate(15)->withQueryString();
        return view('kaprodi.penempatan.index', compact('prodi', 'mahasiswa'));
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $prodi = $this->prodiKaprodi();
        abort_unless((int) $mahasiswa->prodi_id === (int) $prodi->id, 403);

        if ($mahasiswa->status !== 'cuti') {
            return redirect()->route('kaprodi.penempatan.index')
                ->with('status', 'Hanya mahasiswa berstatus Cuti yang dapat diproses melalui penempatan kembali.');
        }

        $mahasiswa->load(['kelasMahasiswa', 'progresStudi', 'riwayatPenempatanKelas.semester', 'statusLog.semester']);
        // Semester 1-2 berada pada tingkat 1, semester 3-4 tingkat 2, dst.
        // Tampilkan kelas aktif pada tingkat yang sesuai dengan progres mahasiswa.
        $semesterBerikutnya = (int) ($mahasiswa->progresStudi?->semester_berikutnya ?? 1);
        $tingkatTujuan = (int) ceil(max(1, $semesterBerikutnya) / 2);

        $kelasList = KelasMahasiswa::where('prodi_id', $prodi->id)
            ->where('status_kelas', 'aktif')
            ->where('tingkat', $tingkatTujuan)
            ->where('id', '!=', $mahasiswa->kelas_mahasiswa_id)
            ->orderBy('angkatan')
            ->orderBy('nama_kelas')
            ->get();
        $semesterList = Semester::with('tahunAkademik')->orderByDesc('tanggal_mulai')->get();

        return view('kaprodi.penempatan.show', compact('prodi', 'mahasiswa', 'kelasList', 'semesterList', 'semesterBerikutnya', 'tingkatTujuan'));
    }

    public function store(Request $request, Mahasiswa $mahasiswa)
    {
        $prodi = $this->prodiKaprodi();
        abort_unless((int) $mahasiswa->prodi_id === (int) $prodi->id, 403);
        if ($mahasiswa->status !== 'cuti') {
            throw ValidationException::withMessages(['mahasiswa' => 'Mahasiswa ini bukan berstatus Cuti atau sudah diproses.']);
        }

        $data = $request->validate([
            'semester_id' => ['required', 'integer', 'exists:semester,id'],
            'kelas_tujuan_id' => ['required', 'integer', 'exists:kelas_mahasiswa,id'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);
        DB::transaction(function () use ($mahasiswa, $data, $prodi) {
            $mahasiswa->refresh();
            if ($mahasiswa->status !== 'cuti') {
                throw ValidationException::withMessages(['mahasiswa' => 'Status mahasiswa sudah berubah. Muat ulang halaman.']);
            }
            $semester = Semester::findOrFail($data['semester_id']);
            $semesterBerikutnya = app(AcademicProgressCalculator::class)->semesterBerjalan($mahasiswa, $semester, true);
            if (! $semesterBerikutnya) {
                throw ValidationException::withMessages(['semester_id' => 'Semester studi tidak dapat dihitung dari angkatan, kalender akademik, dan riwayat Cuti. Periksa data historis mahasiswa.']);
            }

            $tingkatTujuan = (int) ceil(max(1, $semesterBerikutnya) / 2);
            $kelasTujuan = KelasMahasiswa::where('id', $data['kelas_tujuan_id'])
                ->where('prodi_id', $prodi->id)
                ->where('status_kelas', 'aktif')
                ->where('tingkat', $tingkatTujuan)
                ->where('id', '!=', $mahasiswa->kelas_mahasiswa_id)
                ->lockForUpdate()
                ->first();

            if (! $kelasTujuan) {
                throw ValidationException::withMessages([
                    'kelas_tujuan_id' => 'Pilih kelas aktif pada tingkat yang sesuai dengan semester studi mahasiswa (Semester ' . $semesterBerikutnya . ').',
                ]);
            }

            $riwayatCuti = RiwayatCutiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->whereNull('tanggal_selesai')->latest('tanggal_mulai')->first();

            if ($riwayatCuti) {
                $riwayatCuti->update([
                    'tanggal_selesai' => today(),
                    'semester_selesai_id' => $semester->id,
                ]);
            }

            $dataSebelum = [
                'status' => $mahasiswa->status,
                'kelas_mahasiswa_id' => $mahasiswa->kelas_mahasiswa_id,
                'nama_kelas' => $mahasiswa->kelasMahasiswa?->nama_kelas,
            ];
            $mahasiswa->update(['kelas_mahasiswa_id' => $kelasTujuan->id, 'status' => 'aktif']);

            RiwayatAksiAkademik::create([
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_aksi' => 'penempatan_kembali_cuti',
                'data_sebelum' => $dataSebelum,
                'data_sesudah' => [
                    'status' => 'aktif',
                    'kelas_mahasiswa_id' => $kelasTujuan->id,
                    'nama_kelas' => $kelasTujuan->nama_kelas,
                    'angkatan_kelas' => $kelasTujuan->angkatan,
                    'semester_berikutnya' => $semesterBerikutnya,
                    'semester_id' => $semester->id,
                ],
                'alasan' => $data['catatan'] ?? 'Penempatan kembali setelah cuti.',
                'dilakukan_oleh' => auth()->id(),
            ]);

            ProgresStudiMahasiswa::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                [
                    'semester_terakhir_selesai' => max(0, $semesterBerikutnya - 1),
                    'semester_berikutnya' => $semesterBerikutnya,
                    'semester_terakhir_id' => null,
                    'status_progres' => 'berjalan',
                    'catatan' => $data['catatan'] ?? 'Penempatan kembali setelah cuti.',
                ]
            );

            RiwayatPenempatanKelas::create([
                'mahasiswa_id' => $mahasiswa->id,
                'kelas_mahasiswa_id' => $kelasTujuan->id,
                'semester_id' => $semester->id,
                'alasan_penempatan' => 'cuti_kembali',
                'dilakukan_oleh' => auth()->id(),
            ]);

            MahasiswaStatusLog::create([
                'mahasiswa_id' => $mahasiswa->id,
                'semester_id' => $semester->id,
                'status' => 'aktif',
                'catatan' => $data['catatan'] ?? 'Mahasiswa ditempatkan kembali setelah cuti.',
                'dicatat_oleh' => auth()->id(),
                'dicatat_pada' => now(),
            ]);
        });

        return redirect()->route('kaprodi.penempatan.index')
            ->with('status', 'Penempatan berhasil disimpan. Status mahasiswa kembali menjadi Aktif.');
    }
}
