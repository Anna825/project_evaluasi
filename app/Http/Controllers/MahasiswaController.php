<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\KelasMahasiswa;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasMahasiswa = KelasMahasiswa::with('prodi')
            ->withCount('mahasiswa')
            ->orderBy('angkatan', 'desc')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('mahasiswa.index', compact('kelasMahasiswa'));
    }

    public function kelas(\App\Models\KelasMahasiswa $kelas)
    {
        $kelas->load('prodi');

        $mahasiswa = $kelas->mahasiswa()
            ->with('prodi')
            ->orderBy('nim', 'asc')
            ->paginate(15);

        return view('mahasiswa.kelas', compact('kelas', 'mahasiswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodiList = Prodi::orderBy('nama')->get();

        $kelasList = KelasMahasiswa::with('prodi')
            ->orderBy('angkatan', 'desc')
            ->orderBy('nama_kelas')
            ->get();

        return view('mahasiswa.create', compact('prodiList', 'kelasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreMahasiswaRequest $request)
    {
        $data = $request->validated();

        $prodiId = $data['prodi_id'];
        $angkatan = $data['angkatan'];

        if ($data['kelas_mahasiswa_id'] === 'baru') {

            $namaKelas = trim($data['kelas_baru']);

            $kelas = \App\Models\KelasMahasiswa::firstOrCreate(
                [
                    'prodi_id' => $prodiId,
                    'nama_kelas' => $namaKelas,
                    'angkatan' => $angkatan,
                ],
                [
                    'status_kelas' => 'aktif',
                ]
            );

            $data['kelas_mahasiswa_id'] = $kelas->id;
        } else {

            $kelas = \App\Models\KelasMahasiswa::findOrFail(
                $data['kelas_mahasiswa_id']
            );

            if ((int) $kelas->prodi_id !== (int) $prodiId) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'kelas_mahasiswa_id' =>
                            'Kelas yang dipilih tidak sesuai dengan Program Studi mahasiswa.',
                    ]);
            }

            if ((int) $kelas->angkatan !== (int) $angkatan) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'kelas_mahasiswa_id' =>
                            'Kelas yang dipilih tidak sesuai dengan Angkatan mahasiswa.',
                    ]);
            }
        }

        unset($data['kelas_baru']);

        Mahasiswa::create($data);

        return redirect()
            ->route('mahasiswa.index')
            ->with('status', 'Data mahasiswa berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('prodi', 'tracerStudy', 'prestasi');

        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $prodiList = Prodi::all();

        return view('mahasiswa.edit', compact('mahasiswa', 'prodiList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $mahasiswa->update($request->validated());

        return redirect()->route('mahasiswa.index')->with('status', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('status', 'Data mahasiswa berhasil dihapus.');
    }
}