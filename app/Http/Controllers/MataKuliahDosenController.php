<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MataKuliahDosenController extends Controller
{
        /**
     * Pastikan Kaprodi hanya dapat mengakses
     * Mata Kuliah dari Prodi sendiri.
     */
    private function pastikanAksesKaprodi(MataKuliah $mataKuliah): void
    {
        $user = Auth::user();

        // Admin memiliki akses penuh.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            return;
        }

        // Ambil Prodi dari role Kaprodi.
        $kaprodiPivot = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot;

        $prodiId = $kaprodiPivot?->prodi_id;

        if (! $prodiId) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        // Mata Kuliah mengikuti Prodi dari Kurikulumnya.
        $mataKuliah->loadMissing('kurikulum');

        if (
            ! $mataKuliah->kurikulum ||
            (int) $mataKuliah->kurikulum->prodi_id !== (int) $prodiId
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke Mata Kuliah Program Studi lain.'
            );
        }
    }

    /**
     * Menampilkan form tambah mata kuliah untuk kurikulum tertentu.
     */
    public function createForKurikulum(Kurikulum $kurikulum)
    {
        $this->pastikanAksesKurikulum($kurikulum);

        return view('kurikulum.mata-kuliah-create', compact('kurikulum'));
    }

    /**
     * Menyimpan mata kuliah ke kurikulum yang dipilih.
     */
    public function storeForKurikulum(Request $request, Kurikulum $kurikulum)
    {
        $this->pastikanAksesKurikulum($kurikulum);

        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                'unique:mata_kuliah,kode',
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'sks' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],
            'semester_ke' => [
                'required',
                'integer',
                'min:1',
                'max:8',
            ],
            'jenis' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $kurikulum->mataKuliah()->create($validated);

        return redirect()
            ->route('kurikulum.show', $kurikulum)
            ->with('status', 'Mata kuliah berhasil ditambahkan ke kurikulum.');
    }

    /**
     * Memastikan Admin atau Kaprodi hanya mengakses kurikulum yang diizinkan.
     */
    private function pastikanAksesKurikulum(Kurikulum $kurikulum): void
    {
        $user = Auth::user();

        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            return;
        }

        $kaprodiPivot = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot;

        $prodiId = $kaprodiPivot?->prodi_id;

        if (! $prodiId) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        if ((int) $kurikulum->prodi_id !== (int) $prodiId) {
            abort(403, 'Anda tidak memiliki akses ke kurikulum Program Studi lain.');
        }
    }

    /**
     * Menampilkan daftar mata kuliah yang diampu
     * oleh dosen yang sedang login.
     */
    
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        // Mata kuliah dari kelas yang diampu Dosen.
        $mataKuliahDariKelas = $dosen->kelas()
            ->with('mataKuliah')
            ->get()
            ->pluck('mataKuliah')
            ->filter();

        // Mata kuliah yang memiliki CPMK buatan Dosen ini.
        $mataKuliahDariCpmk = \App\Models\Cpmk::query()
            ->where('created_by', $user->id)
            ->with('mataKuliah')
            ->get()
            ->pluck('mataKuliah')
            ->filter();

        // Gabungkan kedua sumber tanpa duplikasi.
        $mataKuliahList = $mataKuliahDariKelas
            ->merge($mataKuliahDariCpmk)
            ->unique('id')
            ->values();

        $kurikulumList = Kurikulum::with('prodi')
            ->where('prodi_id', $dosen->prodi_id)
            ->orderByDesc('tahun_berlaku_mulai')
            ->get();
        $semesterList = Semester::with('tahunAkademik')
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('dosen.mata-kuliah', compact('mataKuliahList', 'kurikulumList', 'semesterList'));
    }

    /**
     * Menampilkan form tambah mata kuliah.
     */
    public function create()
    {
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        /*
         * Ambil kurikulum yang sesuai dengan prodi dosen.
         */
        $kurikulumList = Kurikulum::with('prodi')
            ->where('prodi_id', $dosen->prodi_id)
            ->latest()
            ->get();

        /*
         * Ambil daftar semester akademik.
         */
        $semesterList = Semester::with('tahunAkademik')
            ->latest()
            ->get();

        return view(
            'dosen.mata-kuliah-create',
            compact(
                'dosen',
                'kurikulumList',
                'semesterList'
            )
        );
    }


    /**
     * Menyimpan mata kuliah baru sekaligus
     * membuat kelas yang diampu oleh dosen login.
     */
    public function store(Request $request)
    {
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        $validated = $request->validate([
            'kurikulum_id' => [
                'required',
                'exists:kurikulum,id',
            ],

            'kode' => [
                'required',
                'string',
                'max:50',
                'unique:mata_kuliah,kode',
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'sks' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],

            'semester_ke' => [
                'required',
                'integer',
                'min:1',
                'max:8',
            ],

            'jenis' => [
                'nullable',
                'string',
                'max:50',
            ],

            'semester_id' => [
                'required',
                'exists:semester,id',
            ],

            'nama_kelas' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $kurikulumSesuaiProdi = Kurikulum::whereKey($validated['kurikulum_id'])
            ->where('prodi_id', $dosen->prodi_id)
            ->exists();

        if (! $kurikulumSesuaiProdi) {
            return back()->withInput()->withErrors([
                'kurikulum_id' => 'Mata kuliah hanya dapat dibuat pada kurikulum Program Studi dosen yang sedang login.',
            ]);
        }

        /*
         * Gunakan transaction supaya:
         *
         * 1. Mata Kuliah dibuat
         * 2. Kelas dibuat
         *
         * Kalau salah satunya gagal, semuanya dibatalkan.
         */
        DB::transaction(function () use ($validated, $dosen) {

            $mataKuliah = MataKuliah::create([
                'kurikulum_id' => $validated['kurikulum_id'],
                'kode' => $validated['kode'],
                'nama' => $validated['nama'],
                'sks' => $validated['sks'],
                'semester_ke' => $validated['semester_ke'],
                'jenis' => $validated['jenis'] ?? null,
            ]);

            Kelas::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'semester_id' => $validated['semester_id'],
                'dosen_pengampu_id' => $dosen->id,
                'nama' => $validated['nama_kelas'],
            ]);
        });

        return redirect()
            ->route('mata-kuliah.index')
            ->with(
                'status',
                'Mata kuliah berhasil ditambahkan.'
            );
    }
    
    public function storeKelas(Request $request, MataKuliah $mataKuliah)
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        if (! $user->roles()->where('nama_role', 'dosen')->exists()) {
            abort(403, 'Hanya Dosen yang dapat menambahkan kelas.');
        }

        $validated = $request->validate([
            'kelas_mahasiswa_id' => [
                'required',
                'exists:kelas_mahasiswa,id',
            ],
            'semester_id' => [
                'required',
                'exists:semester,id',
            ],
        ]);

        $kelasMahasiswa = \App\Models\KelasMahasiswa::findOrFail(
            $validated['kelas_mahasiswa_id']
        );

        Kelas::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'kelas_mahasiswa_id' => $kelasMahasiswa->id,
            'semester_id' => $validated['semester_id'],
            'dosen_pengampu_id' => $dosen->id,
            'nama' => $kelasMahasiswa->nama_kelas,
        ]);

        return redirect()
            ->route('mata-kuliah.show', $mataKuliah->id)
            ->with('status', 'Kelas berhasil ditambahkan.');
    }

    /** Menampilkan detail mata kuliah,termasuk CPMK dan RPS.*/
    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load(
            'cpmk.cpl',
            'rps'
        );

        return view(
            'dosen.mata-kuliah-detail',
            compact('mataKuliah')
        );
    }
    public function showKaprodi(MataKuliah $mataKuliah)
    {
        $this->pastikanAksesKaprodi($mataKuliah);

        $mataKuliah->load(
            'cpmk.cpl',
            'rps',
            'kurikulum'
        );

        return view(
            'kurikulum.mata-kuliah-show',
            compact('mataKuliah')
        );
    }
    public function showFromKurikulum(
        Kurikulum $kurikulum,
        MataKuliah $mataKuliah
    ) {
        // Pastikan Mata Kuliah memang berasal
        // dari Kurikulum yang ada di URL.
        if ((int) $mataKuliah->kurikulum_id !== (int) $kurikulum->id) {
            abort(
                403,
                'Mata Kuliah tidak sesuai dengan Kurikulum yang dipilih.'
            );
        }

        $this->pastikanAksesKaprodi($mataKuliah);

        $mataKuliah->load(
            'cpmk.cpl',
            'rps',
            'kurikulum'
        );

        return view(
            'kurikulum.mata-kuliah-show',
            compact('mataKuliah', 'kurikulum')
        );
    }
}