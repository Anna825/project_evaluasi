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
     * Pastikan NIM pada URL sama dengan identitas mahasiswa yang tersimpan
     * pada sesi setelah proses cek NIM.
     */
    private function mahasiswaDalamSesi(string $nim): Mahasiswa
    {
        if ((string) session('public_mahasiswa_nim') !== (string) $nim) {
            abort(403, 'Sesi mahasiswa tidak sesuai. Silakan lakukan pengecekan NIM kembali.');
        }

        return Mahasiswa::with('prodi')
            ->where('nim', $nim)
            ->firstOrFail();
    }

    public function cekNim()
    {
        $stats = [
            'mahasiswa' => Mahasiswa::count(),
            'dosen' => Dosen::count(),
        ];

        return response()
            ->view('public.cek-nim', compact('stats'))
            ->header(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, max-age=0'
            )
            ->header('Pragma', 'no-cache');
    }

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

        if ($mahasiswa->status === 'lulus') {
            return back()->withErrors([
                'nim' => 'Anda sudah berstatus lulus. Silakan mendaftar sebagai Alumni untuk mengakses Tracer Study.',
            ]);
        }

        // Ikat sesi publik ke NIM yang telah melewati halaman cek NIM.
        $request->session()->regenerate();
        $request->session()->put('public_mahasiswa_nim', (string) $mahasiswa->nim);

        return redirect()->route(
            'public.mahasiswa.menu',
            $mahasiswa->nim
        );
    }

   public function menu(string $nim)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);
        if ($mahasiswa->status === 'lulus') {
            return redirect()
                ->route('mahasiswa.login')
                ->withErrors([
                    'nim' => 'Anda sudah berstatus lulus. Silakan mendaftar atau login melalui akun Alumni.',
                ]);
        }

        $prestasiCount = $mahasiswa->prestasi()->count();
        return view(
            'public.dashboard',
            compact(
                'mahasiswa',
                'prestasiCount'
            )
        );
    }

    public function prestasiIndex(string $nim)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);

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

    public function prestasiShow(string $nim, Prestasi $prestasi)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);
        if (! $prestasi->mahasiswa()->whereKey($mahasiswa->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }
        $prestasi->load([
            'mahasiswa',
            'tahunAkademik',
            'dosenPembimbing',
        ]);
        return view(
            'public.prestasi-show',
            compact(
                'mahasiswa',
                'prestasi'
            )
        );
    }

    public function prestasiEdit(string $nim, Prestasi $prestasi)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);
        if (! $prestasi->mahasiswa()->whereKey($mahasiswa->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }
        $tahunAkademikList = TahunAkademik::all();
        $dosenList = Dosen::orderBy('nama')->get();
        $prestasi->load([
            'dosenPembimbing',
            'tahunAkademik',
        ]);
        return view(
            'public.prestasi-edit',
            compact(
                'mahasiswa',
                'prestasi',
                'tahunAkademikList',
                'dosenList'
            )
        );
    }

    public function prestasiUpdate(Request $request, string $nim, Prestasi $prestasi)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);
        if (! $prestasi->mahasiswa()->whereKey($mahasiswa->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }
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

            'tanggal_penerimaan' => [
                'nullable',
                'date',
            ],

            'tahun_akademik_id' => [
                'required',
                'exists:tahun_akademik,id',
            ],

            'dosen_id' => [
                'nullable',
                'exists:dosen,id',
            ],
        ]);

        $dosenId = $validated['dosen_id'] ?? null;
        unset($validated['dosen_id']);
        $prestasi->update($validated);
        $prestasi->dosenPembimbing()->detach();
        if ($dosenId) {
            $prestasi->dosenPembimbing()->attach(
                $dosenId,
                [
                    'mahasiswa_id' => $mahasiswa->id,
                ]
            );
        }
        return redirect()
            ->route('public.prestasi.show', [
                $mahasiswa->nim,
                $prestasi->id,
            ])
            ->with(
                'status',
                'Prestasi berhasil diperbarui.'
            );
    }

    public function prestasiDestroy(string $nim, Prestasi $prestasi)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);        // Pastikan prestasi milik mahasiswa ini
        if (! $prestasi->mahasiswa()->whereKey($mahasiswa->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }
        $prestasi->mahasiswa()->detach($mahasiswa->id);
        $prestasi->dosenPembimbing()
            ->wherePivot('mahasiswa_id', $mahasiswa->id)
            ->detach();
        $prestasi->delete();
        return redirect()
            ->route('public.prestasi.index', $mahasiswa->nim)
            ->with(
                'status',
                'Prestasi berhasil dihapus.'
            );
    }

    public function profil(string $nim)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);
        return view(
            'public.profil',
            compact('mahasiswa')
        );
    }

    public function prestasiCreate(string $nim)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);
        $tahunAkademikList = TahunAkademik::all();
        $dosenList = Dosen::orderBy('nama')->get();
        return view(
            'public.prestasi-create',
            compact(
                'mahasiswa',
                'tahunAkademikList',
                'dosenList'
            )
        );
    }

    public function prestasiStore(Request $request, string $nim)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);

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

            'tanggal_penerimaan' => [
                'nullable',
                'date',
            ],

            'tahun_akademik_id' => [
                'required',
                'exists:tahun_akademik,id',
            ],

            'dosen_id' => [
                'nullable',
                'exists:dosen,id',
            ],
        ]);
        $dosenId = $validated['dosen_id'] ?? null;
        unset($validated['dosen_id']);
        $prestasi = Prestasi::create($validated);
        $prestasi->mahasiswa()->attach($mahasiswa->id);
        if ($dosenId) {
            $prestasi->dosenPembimbing()->attach(
                $dosenId,
                [
                    'mahasiswa_id' => $mahasiswa->id,
                ]
            );
        }

        return redirect()
            ->route('public.prestasi.index', $nim)
            ->with(
                'status',
                'Prestasi berhasil dicatat. Terima kasih!'
            );
    }
    public function tracerCreate(string $nim)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);

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

    public function tracerStore(Request $request, string $nim)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);

        if ($mahasiswa->status !== 'lulus') {
            abort(
                403,
                'Tracer study hanya untuk alumni yang sudah lulus.'
            );
        }

        $sudahPernahIsi = $mahasiswa
            ->tracerStudy()
            ->exists();

        $jenisPengisian = $sudahPernahIsi
            ? 'perubahan_karier'
            : 'awal';

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

        $validated['mahasiswa_id'] = $mahasiswa->id;
        $validated['tanggal_pengisian'] = now()->toDateString();
        $validated['jenis_pengisian'] = $jenisPengisian;

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
        $validated['periode_pelacakan'] = now()->format('Y');
        if ($jenisPengisian === 'awal') {
            if ((int) $validated['prodi_id'] !== (int) $mahasiswa->prodi_id) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'prodi_id' =>
                            'Program Studi tidak sesuai dengan data mahasiswa.',
                    ]);
            }
        }
        TracerStudy::create($validated);
        $message = $jenisPengisian === 'awal'
            ? 'Tracer Study berhasil dicatat. Terima kasih!'
            : 'Riwayat perubahan pekerjaan berhasil ditambahkan. Terima kasih!';
        return redirect()
            ->route('public.tracer.create', $nim)
            ->with('status', $message);
    }
    public function tracerDestroy(string $nim, TracerStudy $tracer)
    {
        $mahasiswa = $this->mahasiswaDalamSesi($nim);
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