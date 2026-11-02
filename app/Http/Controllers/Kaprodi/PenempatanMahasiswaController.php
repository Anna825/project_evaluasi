<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MahasiswaStatusLog;
use App\Models\ProgresStudiMahasiswa;
use App\Models\RiwayatPenempatanKelas;
use App\Models\Semester;
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
        $query = Mahasiswa::with(['kelasMahasiswa', 'progresStudi'])
            ->where('prodi_id', $prodi->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('penempatan')) {
            if ($request->penempatan === 'belum') {
                $query->where('status', 'cuti');
            } elseif ($request->penempatan === 'selesai') {
                $query->where('status', 'aktif')->whereHas('riwayatPenempatanKelas', fn ($q) => $q->where('alasan_penempatan', 'cuti_kembali'));
            }
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(fn ($q) => $q->where('nim', 'like', "%{$keyword}%")->orWhere('nama', 'like', "%{$keyword}%"));
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
        $kelasList = KelasMahasiswa::where('prodi_id', $prodi->id)
            ->where('status_kelas', 'aktif')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $semesterList = Semester::with('tahunAkademik')->orderByDesc('tanggal_mulai')->get();

        return view('kaprodi.penempatan.show', compact('prodi', 'mahasiswa', 'kelasList', 'semesterList'));
    }

    public function store(Request $request, Mahasiswa $mahasiswa)
    {
        $prodi = $this->prodiKaprodi();
        abort_unless((int) $mahasiswa->prodi_id === (int) $prodi->id, 403);
        if ($mahasiswa->status !== 'cuti') {
            throw ValidationException::withMessages(['mahasiswa' => 'Mahasiswa ini bukan berstatus Cuti atau sudah diproses.']);
        }

        $data = $request->validate([
            'kelas_tujuan_id' => ['required', 'integer', 'exists:kelas_mahasiswa,id'],
            'semester_id' => ['required', 'integer', 'exists:semester,id'],
            'semester_berikutnya' => ['required', 'integer', 'min:1', 'max:14'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);
        $kelasTujuan = KelasMahasiswa::where('id', $data['kelas_tujuan_id'])
            ->where('prodi_id', $prodi->id)->where('status_kelas', 'aktif')->firstOrFail();

        DB::transaction(function () use ($mahasiswa, $data, $kelasTujuan) {
            $mahasiswa->refresh();
            if ($mahasiswa->status !== 'cuti') {
                throw ValidationException::withMessages(['mahasiswa' => 'Status mahasiswa sudah berubah. Muat ulang halaman.']);
            }
            $semester = Semester::findOrFail($data['semester_id']);
            $mahasiswa->update(['kelas_mahasiswa_id' => $kelasTujuan->id, 'status' => 'aktif']);

            ProgresStudiMahasiswa::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                [
                    'semester_terakhir_selesai' => max(0, $data['semester_berikutnya'] - 1),
                    'semester_berikutnya' => $data['semester_berikutnya'],
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
