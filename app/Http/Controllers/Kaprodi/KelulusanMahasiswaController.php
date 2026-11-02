<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\RiwayatAksiAkademik;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KelulusanMahasiswaController extends Controller
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
            ->whereNotNull('angkatan_masuk')->distinct()->orderByDesc('angkatan_masuk')
            ->pluck('angkatan_masuk');
        $query = Mahasiswa::with('kelasMahasiswa')
            ->where('prodi_id', $prodi->id)->where('status', 'aktif');

        if ($request->filled('angkatan')) {
            $query->where('angkatan_masuk', $request->integer('angkatan'));
        }

        $mahasiswa = $query->orderBy('angkatan_masuk')->orderBy('nim')->paginate(30)->withQueryString();

        return view('kaprodi.kelulusan.index', compact('prodi', 'angkatanList', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $prodi = $this->prodiKaprodi();
        $data = $request->validate([
            'mahasiswa_ids' => ['required', 'array', 'min:1'],
            'mahasiswa_ids.*' => ['required', 'integer', 'distinct'],
            'tanggal_lulus' => ['required', 'date'],
            'alasan' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($data, $prodi) {
            $mahasiswaList = Mahasiswa::where('prodi_id', $prodi->id)
                ->whereIn('id', $data['mahasiswa_ids'])
                ->where('status', 'aktif')->lockForUpdate()->get();

            if ($mahasiswaList->count() !== count($data['mahasiswa_ids'])) {
                throw ValidationException::withMessages([
                    'mahasiswa_ids' => 'Sebagian mahasiswa tidak ditemukan, bukan berstatus Aktif, atau bukan dari Program Studi Anda. Muat ulang daftar.',
                ]);
            }

            foreach ($mahasiswaList as $mahasiswa) {
                $before = [
                    'status' => $mahasiswa->status,
                    'kelas_mahasiswa_id' => $mahasiswa->kelas_mahasiswa_id,
                    'angkatan_masuk' => $mahasiswa->angkatan_masuk,
                ];

                $mahasiswa->update(['status' => 'lulus']);

                DB::table('riwayat_kelulusan_mahasiswa')->insert([
                    'mahasiswa_id' => $mahasiswa->id,
                    'tanggal_lulus' => $data['tanggal_lulus'],
                    'catatan' => $data['alasan'],
                    'ditetapkan_oleh' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                RiwayatAksiAkademik::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'jenis_aksi' => 'penetapan_kelulusan',
                    'data_sebelum' => $before,
                    'data_sesudah' => [
                        'status' => 'lulus',
                        'tanggal_lulus' => $data['tanggal_lulus'],
                    ],
                    'alasan' => $data['alasan'],
                    'dilakukan_oleh' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('kaprodi.kelulusan.index')
            ->with('status', 'Penetapan kelulusan berhasil disimpan untuk mahasiswa yang dipilih.');
    }
}
