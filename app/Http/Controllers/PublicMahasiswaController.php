<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prestasi;
use App\Models\TahunAkademik;
use App\Models\TracerStudy;
use Illuminate\Http\Request;
use App\Models\Prodi;

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
        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', $nim)
            ->firstOrFail();

        if ($mahasiswa->status !== 'lulus') {
            return redirect()
                ->route('public.mahasiswa.menu', $nim)
                ->with(
                    'error',
                    'Tracer Study hanya dapat diisi oleh alumni yang sudah berstatus lulus.'
                );
        }

        $tracerStudies = $mahasiswa
            ->tracerStudy()
            ->with('prodi')
            ->latest('created_at')
            ->get();

        $prodiList = Prodi::orderBy('nama')->get();

        return view(
            'public.tracer-create',
            compact(
                'mahasiswa',
                'tracerStudies',
                'prodiList'
            )
        );
    }

    /**
     * Menyimpan data Tracer Study.
     */
    public function tracerStore(Request $request, string $nim)
    {
        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', $nim)
            ->firstOrFail();

        if ($mahasiswa->status !== 'lulus') {
            abort(
                403,
                'Tracer study hanya untuk alumni yang sudah lulus.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tentukan jenis pengisian
        |--------------------------------------------------------------------------
        |
        | Belum pernah mengisi  → Pengisian Awal
        | Sudah pernah mengisi  → Perubahan Karier
        |
        */

        $sudahPernahIsi = $mahasiswa
            ->tracerStudy()
            ->exists();

        $jenisPengisian = $sudahPernahIsi
            ? 'perubahan_karier'
            : 'awal';

        /*
        |--------------------------------------------------------------------------
        | Validasi data
        |--------------------------------------------------------------------------
        */

        if ($jenisPengisian === 'awal') {

            $validated = $request->validate([

                // Data alumni
                'nama_alumni' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'tahun_masuk' => [
                    'required',
                    'integer',
                    'min:1900',
                    'max:2100',
                ],

                'tahun_lulus' => [
                    'required',
                    'integer',
                    'min:1900',
                    'max:2100',
                ],

                'prodi_id' => [
                    'required',
                    'exists:prodi,id',
                ],

                // Pendidikan S2
                'melanjutkan_s2' => [
                    'nullable',
                    'string',
                    'max:10',
                ],

                'perguruan_tinggi_s2' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'prodi_s2' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                // Pekerjaan pertama
                'waktu_tunggu_kerja_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'sumber_lowongan_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'sumber_lowongan_pertama_lainnya' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'jenis_pekerjaan_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'jenis_pekerjaan_pertama_lainnya' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'nama_instansi_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'tingkat_perusahaan_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'kesesuaian_bidang_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'gaji_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'posisi_pertama' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]);

        } else {

            $validated = $request->validate([

                // Pekerjaan saat ini
                'jenis_pekerjaan_saat_ini' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'jenis_pekerjaan_saat_ini_lainnya' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'nama_instansi_saat_ini' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'tingkat_perusahaan_saat_ini' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'jumlah_pindah_kerja' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'alasan_pindah' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'alasan_pindah_lainnya' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'sumber_lowongan_saat_ini' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'sumber_lowongan_saat_ini_lainnya' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'gaji_saat_ini' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'posisi_saat_ini' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Data yang otomatis berasal dari mahasiswa
        |--------------------------------------------------------------------------
        */

        $validated['mahasiswa_id'] = $mahasiswa->id;
        $validated['tanggal_pengisian'] = now()->toDateString();
        $validated['jenis_pengisian'] = $jenisPengisian;

        // Jika perubahan karier, gunakan snapshot data dari pengisian awal
        if ($jenisPengisian === 'perubahan_karier') {
            $tracerAwal = $mahasiswa->tracerStudy()
                ->where('jenis_pengisian', 'awal')
                ->latest('tanggal_pengisian')
                ->first();

            if (! $tracerAwal) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'tracer' => 'Data Tracer Study awal belum ditemukan.',
                    ]);
            }

            $validated['nama_alumni'] = $tracerAwal->nama_alumni;
            $validated['tahun_masuk'] = $tracerAwal->tahun_masuk;
            $validated['tahun_lulus'] = $tracerAwal->tahun_lulus;
            $validated['prodi_id'] = $tracerAwal->prodi_id;
        }

        /*
        |--------------------------------------------------------------------------
        | Periode pelacakan
        |--------------------------------------------------------------------------
        */

        $validated['periode_pelacakan'] = now()->format('Y');

        /*
        |--------------------------------------------------------------------------
        | Jika pengisian awal, simpan snapshot data alumni
        |--------------------------------------------------------------------------
        */

        if ($jenisPengisian === 'awal') {

            /*
            | Untuk keamanan, Prodi yang dikirim dari form harus sama
            | dengan Prodi mahasiswa.
            */

            if ((int) $validated['prodi_id'] !== (int) $mahasiswa->prodi_id) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'prodi_id' =>
                            'Program Studi tidak sesuai dengan data mahasiswa.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan sebagai record BARU
        |--------------------------------------------------------------------------
        |
        | Tidak ada update terhadap tracer lama.
        | Setiap pengisian menghasilkan satu riwayat baru.
        |
        */

        TracerStudy::create($validated);

        $message = $jenisPengisian === 'awal'
            ? 'Tracer Study berhasil dicatat. Terima kasih!'
            : 'Riwayat perubahan pekerjaan berhasil ditambahkan. Terima kasih!';

        return redirect()
            ->route('public.tracer.create', $nim)
            ->with('status', $message);
    }
    /**
     * Menghapus satu riwayat Tracer Study.
     */
    public function tracerDestroy(string $nim, TracerStudy $tracer)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        // Pastikan riwayat yang dihapus benar-benar milik mahasiswa tersebut.
        if ($tracer->mahasiswa_id !== $mahasiswa->id) {
            abort(403);
        }

        $tracer->delete();

        return redirect()
            ->route('public.tracer.create', $nim)
            ->with('status', 'Riwayat Tracer Study berhasil dihapus.');
    }
}