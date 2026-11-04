<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\ProgresStudiMahasiswa;
use App\Models\RiwayatAksiAkademik;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ValidasiProgresAkademikController extends Controller
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

    private function analisis(Mahasiswa $mahasiswa): array
    {
        $tingkat = (int) ($mahasiswa->kelasMahasiswa?->tingkat
            ?: \App\Models\KelasMahasiswa::tingkatDariNama($mahasiswa->kelasMahasiswa?->nama_kelas ?? ''));

        $batasSemester = $tingkat > 0 ? $tingkat * 2 : 0;
        $riwayat = DB::table('riwayat_detail_penyelesaian_semester as detail')
            ->join('semester', 'semester.id', '=', 'detail.semester_id')
            ->where('detail.mahasiswa_id', $mahasiswa->id)
            ->orderBy('detail.created_at')
            ->orderBy('detail.id')
            ->select('detail.*', 'semester.jenis')
            ->get();

        $semesterValid = 0;
        $detailValidTerakhir = null;
        $anomali = [];

        foreach ($riwayat as $detail) {
            $nomor = (int) $detail->semester_diselesaikan;
            $berikutnya = (int) $detail->semester_berikutnya;
            $jenisSesuai = $nomor % 2 === 1
                ? strtolower($detail->jenis) === 'ganjil'
                : strtolower($detail->jenis) === 'genap';

            if ($nomor !== $semesterValid + 1 || $berikutnya !== $nomor + 1 || ! $jenisSesuai) {
                $anomali[] = $detail;
                continue;
            }

            if ($batasSemester > 0 && $nomor > $batasSemester) {
                $anomali[] = $detail;
                continue;
            }

            $semesterValid = $nomor;
            $detailValidTerakhir = $detail;
        }

        $progres = $mahasiswa->progresStudi;
        $saatIni = (int) ($progres?->semester_terakhir_selesai ?? 0);
        $berikutnya = (int) ($progres?->semester_berikutnya ?? 1);
        $semesterId = $progres?->semester_terakhir_id;
        $tidakSinkron = $saatIni !== $semesterValid
            || $berikutnya !== ($semesterValid + 1)
            || ($semesterValid > 0 && (int) $semesterId !== (int) $detailValidTerakhir?->semester_id)
            || ($semesterValid === 0 && $semesterId !== null);

        $butuhPerbaikan = $tidakSinkron || count($anomali) > 0;

        return [
            'tingkat' => $tingkat,
            'batas_semester' => $batasSemester,
            'progres_sekarang' => $progres,
            'semester_valid' => $semesterValid,
            'semester_berikutnya_valid' => $semesterValid + 1,
            'detail_valid_terakhir' => $detailValidTerakhir,
            'anomali' => $anomali,
            'butuh_perbaikan' => $butuhPerbaikan,
            'alasan' => $batasSemester > 0 && $semesterValid < (int) ($riwayat->max('semester_diselesaikan') ?? 0)
                ? 'Ditemukan riwayat semester yang melampaui tingkat kelas saat ini atau tidak membentuk urutan yang valid.'
                : ($tidakSinkron ? 'Data progres studi berbeda dengan riwayat penyelesaian semester yang valid.' : 'Terdapat catatan riwayat yang perlu ditinjau.'),
        ];
    }

    public function index(Request $request)
    {
        $prodi = $this->prodiKaprodi();
        $mahasiswaList = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
            ->where('prodi_id', $prodi->id)
            ->where('status', 'aktif')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim($request->q);
                $query->where(fn ($q) => $q->where('nim', 'like', '%' . $term . '%')
                    ->orWhere('nama', 'like', '%' . $term . '%'));
            })
            ->orderBy('nama')->get();

        $items = $mahasiswaList->map(function ($mahasiswa) {
            return ['mahasiswa' => $mahasiswa, 'analisis' => $this->analisis($mahasiswa)];
        })->filter(fn ($item) => $item['analisis']['butuh_perbaikan'])->values();

        return view('kaprodi.validasi-progres.index', [
            'prodi' => $prodi,
            'items' => $items,
            'totalAktif' => $mahasiswaList->count(),
            'q' => $request->q,
        ]);
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $prodi = $this->prodiKaprodi();
        abort_unless($mahasiswa->prodi_id === $prodi->id, 404);
        $mahasiswa->load(['kelasMahasiswa', 'progresStudi']);
        $analisis = $this->analisis($mahasiswa);

        $riwayat = DB::table('riwayat_detail_penyelesaian_semester as detail')
            ->join('semester', 'semester.id', '=', 'detail.semester_id')
            ->join('riwayat_batch_penyelesaian_semester as batch', 'batch.id', '=', 'detail.batch_id')
            ->where('detail.mahasiswa_id', $mahasiswa->id)
            ->orderBy('detail.created_at')->orderBy('detail.id')
            ->select('detail.*', 'semester.jenis', 'semester.tahun_akademik_id', 'batch.id as batch_nomor')
            ->get();

        return view('kaprodi.validasi-progres.show', compact('prodi', 'mahasiswa', 'analisis', 'riwayat'));
    }

    public function sync(Request $request, Mahasiswa $mahasiswa)
    {
        $prodi = $this->prodiKaprodi();
        abort_unless($mahasiswa->prodi_id === $prodi->id, 404);
        $data = $request->validate([
            'alasan' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        DB::transaction(function () use ($mahasiswa, $data) {
            $mahasiswa = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
                ->whereKey($mahasiswa->id)->lockForUpdate()->firstOrFail();
            $analisis = $this->analisis($mahasiswa);

            if (! $analisis['butuh_perbaikan']) {
                throw ValidationException::withMessages([
                    'mahasiswa' => 'Data progres sudah sinkron. Tidak ada perubahan yang diperlukan.',
                ]);
            }

            $progres = $mahasiswa->progresStudi;
            $before = [
                'semester_terakhir_selesai' => $progres?->semester_terakhir_selesai,
                'semester_berikutnya' => $progres?->semester_berikutnya,
                'semester_terakhir_id' => $progres?->semester_terakhir_id,
                'status_progres' => $progres?->status_progres,
            ];
            $semesterValid = $analisis['semester_valid'];
            $detailTerakhir = $analisis['detail_valid_terakhir'];

            $after = [
                'semester_terakhir_selesai' => $semesterValid,
                'semester_berikutnya' => $semesterValid + 1,
                'semester_terakhir_id' => $detailTerakhir?->semester_id,
                'status_progres' => 'berjalan',
            ];

            ProgresStudiMahasiswa::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                $after
            );

            RiwayatAksiAkademik::create([
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_aksi' => 'sinkronisasi_progres',
                'data_sebelum' => $before,
                'data_sesudah' => $after,
                'alasan' => $data['alasan'] . ' Sinkronisasi berdasarkan riwayat penyelesaian semester valid terakhir.',
                'dilakukan_oleh' => auth()->id(),
            ]);
        });

        return redirect()->route('kaprodi.validasi-progres.show', $mahasiswa)
            ->with('status', 'Progres akademik berhasil disinkronkan. Riwayat penyelesaian semester tidak dihapus.');
    }
}
