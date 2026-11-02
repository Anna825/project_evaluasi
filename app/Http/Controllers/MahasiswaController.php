<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Semester;
use App\Models\RiwayatAksiAkademik;
use App\Models\RiwayatCutiMahasiswa;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index()
    {
        $kelasMahasiswa = KelasMahasiswa::with([
            'prodi',
            'mahasiswa:id,kelas_mahasiswa_id,angkatan_masuk',
        ])
            ->withCount('mahasiswa')
            ->orderBy('prodi_id')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        // Angkatan pada kartu kelas adalah angkatan mayoritas mahasiswa,
        // bukan identitas kelas. Jika jumlah sama, pilih tahun yang lebih baru.
        $kelasMahasiswa->each(function ($kelas) {
            $kelas->angkatan_mayoritas = $kelas->mahasiswa
                ->pluck('angkatan_masuk')
                ->filter(fn ($tahun) => $tahun !== null && $tahun !== '')
                ->countBy()
                ->sort(function ($jumlahA, $jumlahB) {
                    return $jumlahB <=> $jumlahA;
                });

            $frekuensi = $kelas->angkatan_mayoritas;
            $tahunMayoritas = $frekuensi->isEmpty()
                ? null
                : $frekuensi->sortKeysDesc()->sortByDesc(fn ($jumlah) => $jumlah)->keys()->first();

            $kelas->angkatan_mayoritas = $tahunMayoritas;
        });

        return view('mahasiswa.index', compact('kelasMahasiswa'));
    }

    public function kelas(KelasMahasiswa $kelas)
    {
        $kelas->load('prodi');

        $mahasiswa = $kelas->mahasiswa()
            ->with('prodi')
            ->orderBy('angkatan_masuk')
            ->orderBy('nim')
            ->paginate(15);

        return view('mahasiswa.kelas', compact('kelas', 'mahasiswa'));
    }

    public function create()
    {
        $prodiList = Prodi::orderBy('nama')->get();

        $kelasList = KelasMahasiswa::with('prodi')
            ->orderBy('prodi_id')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('mahasiswa.create', compact('prodiList', 'kelasList'));
    }

    public function store(StoreMahasiswaRequest $request)
    {
        $data = $request->validated();
        $prodiId = (int) $data['prodi_id'];
        $angkatanMasuk = (int) $data['angkatan'];

        if ($data['kelas_mahasiswa_id'] === 'baru') {
            $namaKelas = trim($data['kelas_baru']);

            $kelas = KelasMahasiswa::firstOrCreate(
                ['prodi_id' => $prodiId, 'nama_kelas' => $namaKelas],
                [
                    'tingkat' => KelasMahasiswa::tingkatDariNama($namaKelas),
                    'status_kelas' => 'aktif',
                    'angkatan' => $angkatanMasuk,
                ]
            );
        } else {
            $kelas = KelasMahasiswa::findOrFail($data['kelas_mahasiswa_id']);

            if ((int) $kelas->prodi_id !== $prodiId) {
                return back()->withInput()->withErrors([
                    'kelas_mahasiswa_id' => 'Kelas yang dipilih tidak sesuai dengan Program Studi mahasiswa.',
                ]);
            }
        }

        Mahasiswa::create([
            'prodi_id' => $prodiId,
            'kelas_mahasiswa_id' => $kelas->id,
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'angkatan_masuk' => $angkatanMasuk,
            'status' => $data['status'],
        ]);

        return redirect()->route('mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('prodi', 'tracerStudy', 'prestasi');

        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodiList = Prodi::all();

        return view('mahasiswa.edit', compact('mahasiswa', 'prodiList'));
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validated();
        $data['angkatan_masuk'] = (int) $data['angkatan'];
        unset($data['angkatan']);

        $statusSebelum = $mahasiswa->status;
        $statusSesudah = $data['status'];
        $snapshotSebelum = [
            'status' => $mahasiswa->status,
            'kelas_mahasiswa_id' => $mahasiswa->kelas_mahasiswa_id,
            'prodi_id' => $mahasiswa->prodi_id,
            'angkatan_masuk' => $mahasiswa->angkatan_masuk,
        ];

        DB::transaction(function () use ($mahasiswa, $data, $statusSebelum, $statusSesudah, $snapshotSebelum) {
            if ($statusSebelum !== $statusSesudah && $statusSebelum === 'aktif' && $statusSesudah === 'cuti') {
                $semesterBerjalan = Semester::whereDate('tanggal_mulai', '<=', today())
                    ->whereDate('tanggal_selesai', '>=', today())
                    ->first();

                RiwayatCutiMahasiswa::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester_mulai_id' => $semesterBerjalan?->id,
                    'tanggal_mulai' => today(),
                    'catatan' => 'Perubahan status menjadi Cuti.',
                    'dicatat_oleh' => auth()->id(),
                ]);
            }

            if ($statusSebelum === 'cuti' && $statusSesudah === 'aktif') {
                $riwayatCuti = RiwayatCutiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                    ->whereNull('tanggal_selesai')
                    ->latest('tanggal_mulai')
                    ->first();

                if ($riwayatCuti) {
                    $semesterBerjalan = Semester::whereDate('tanggal_mulai', '<=', today())
                        ->whereDate('tanggal_selesai', '>=', today())
                        ->first();

                    $riwayatCuti->update([
                        'tanggal_selesai' => today(),
                        'semester_selesai_id' => $semesterBerjalan?->id,
                    ]);
                }
            }

            $mahasiswa->update($data);

            if ($statusSebelum !== $statusSesudah) {
                RiwayatAksiAkademik::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'jenis_aksi' => 'perubahan_status',
                    'data_sebelum' => $snapshotSebelum,
                    'data_sesudah' => [
                        'status' => $mahasiswa->status,
                        'kelas_mahasiswa_id' => $mahasiswa->kelas_mahasiswa_id,
                        'prodi_id' => $mahasiswa->prodi_id,
                        'angkatan_masuk' => $mahasiswa->angkatan_masuk,
                    ],
                    'alasan' => 'Perubahan status melalui formulir edit data mahasiswa.',
                    'dilakukan_oleh' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil dihapus.');
    }
}
