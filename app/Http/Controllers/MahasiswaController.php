<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Prodi;

class MahasiswaController extends Controller
{
    public function index()
    {
        $kelasMahasiswa = KelasMahasiswa::with('prodi')
            ->withCount('mahasiswa')
            ->orderBy('prodi_id')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

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

        $mahasiswa->update($data);

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
