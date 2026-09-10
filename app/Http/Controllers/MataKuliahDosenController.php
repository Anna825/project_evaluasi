<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MataKuliahDosenController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah yang diampu
     * oleh dosen yang sedang login.
     */
    public function index()
    {
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        $kelasList = $dosen->kelas()
            ->with('mataKuliah')
            ->get();

        $mataKuliahList = $kelasList
            ->pluck('mataKuliah')
            ->unique('id')
            ->values();

        return view('dosen.mata-kuliah', compact('mataKuliahList'));
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
        $kurikulumList = Kurikulum::where('prodi_id', $dosen->prodi_id)
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


    /**
     * Menampilkan detail mata kuliah,
     * termasuk CPMK dan RPS.
     */
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
    public function showFromKurikulum(Kurikulum $kurikulum, MataKuliah $mataKuliah)
    {
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