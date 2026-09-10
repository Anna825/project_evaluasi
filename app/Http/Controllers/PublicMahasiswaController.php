<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prestasi;
use App\Models\TahunAkademik;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class PublicMahasiswaController extends Controller
{
    /**
     * Form input NIM untuk masuk ke halaman self-service.
     */
    public function cekNim()
    {
        $stats = [
            'mahasiswa' => Mahasiswa::count(),
            'dosen' => Dosen::count(),
            'akreditasi' => 'A',
        ];

        return response()
            ->view('public.cek-nim', compact('stats'))
            ->header(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, max-age=0'
            )
            ->header('Pragma', 'no-cache');
    }

    /**
     * Proses cek NIM, arahkan ke halaman yang sesuai.
     */
    public function prosesCekNim(Request $request)
    {
        $request->validate([
            'nim' => ['required', 'string'],
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (! $mahasiswa) {
            return back()->withErrors([
                'nim' => 'NIM tidak ditemukan.',
            ]);
        }

        return redirect()->route(
            'public.mahasiswa.menu',
            $mahasiswa->nim
        );
    }

    /**
     * Menu pilihan setelah NIM valid
     * (Isi Prestasi / Isi Tracer Study).
     */
    public function menu(string $nim)
    {
        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', $nim)
            ->firstOrFail();

        $prestasiCount = $mahasiswa->prestasi()->count();

        $tracerStudy = $mahasiswa->tracerStudy()
            ->latest()
            ->first();

        $tracerSudahIsi = $tracerStudy !== null;

        return view(
            'public.dashboard',
            compact(
                'mahasiswa',
                'prestasiCount',
                'tracerStudy',
                'tracerSudahIsi'
            )
        );    
    }

    /**
     * Form isi Prestasi.
     */
    public function prestasiIndex(string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $prestasiList = $mahasiswa
            ->prestasi()
            ->with('tahunAkademik')
            ->latest()
            ->get();

        return view(
            'public.prestasi-index',
            compact('mahasiswa', 'prestasiList')
        );
    }

    /**
     * Menampilkan profil mahasiswa.
     */
    public function profil(string $nim)
    {
        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', $nim)
            ->firstOrFail();

        return view(
            'public.profil',
            compact('mahasiswa')
        );
    }

    /**
     * Form input Prestasi.
     */
    public function prestasiCreate(string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $tahunAkademikList = TahunAkademik::all();

        return view(
            'public.prestasi-create',
            compact('mahasiswa', 'tahunAkademikList')
        );
    }

    /**
     * Menyimpan data Prestasi.
     */
    public function prestasiStore(Request $request, string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
            'tingkat' => [
                'nullable',
                'string',
                'max:255',
            ],
            'jenis' => [
                'nullable',
                'string',
                'max:255',
            ],
            'peringkat' => [
                'nullable',
                'string',
                'max:255',
            ],
            'tempat_pelaksanaan' => [
                'nullable',
                'string',
                'max:255',
            ],
            'tahun_akademik_id' => [
                'required',
                'exists:tahun_akademik,id',
            ],
        ]);

        $prestasi = Prestasi::create($validated);

        $prestasi->mahasiswa()->attach($mahasiswa->id);

        return redirect()
            ->route('public.mahasiswa.menu', $nim)
            ->with(
                'status',
                'Prestasi berhasil dicatat. Terima kasih!'
            );
    }

    /**
     * Form isi Tracer Study
     * (hanya untuk mahasiswa berstatus lulus).
     */
    public function tracerCreate(string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $tracerStudy = $mahasiswa
            ->tracerStudy()
            ->latest()
            ->first();

        if ($mahasiswa->status !== 'lulus') {
            return redirect()
                ->route('public.mahasiswa.menu', $nim)
                ->with(
                    'error',
                    'Tracer Study hanya dapat diisi oleh alumni yang sudah berstatus lulus.'
                );
        }

        return view(
            'public.tracer-create',
            compact('mahasiswa', 'tracerStudy')
        );
    }

    /**
     * Menyimpan data Tracer Study.
     */
    public function tracerStore(Request $request, string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        if ($mahasiswa->status !== 'lulus') {
            abort(
                403,
                'Tracer study hanya untuk alumni yang sudah lulus.'
            );
        }

        $validated = $request->validate([
            'periode_pelacakan' => [
                'required',
                'string',
                'max:20',
            ],

            'status_utama' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nama_instansi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kesesuaian_bidang' => [
                'nullable',
                'string',
                'max:255',
            ],

            'rentang_pendapatan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $tracerStudy = $mahasiswa->tracerStudy()
            ->latest()
            ->first();

        if ($tracerStudy) {
            $tracerStudy->update($validated);

            $message = 'Data Tracer Study berhasil diperbarui.';
        } else {
            $validated['mahasiswa_id'] = $mahasiswa->id;

            TracerStudy::create($validated);

            $message = 'Tracer Study berhasil dicatat. Terima kasih!';
        }

        return redirect()
            ->route('public.mahasiswa.menu', $nim)
            ->with('status', $message);

    }
}