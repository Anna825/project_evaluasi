<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNilaiCpmkRequest;
use App\Models\Kelas;
use App\Models\NilaiCpmk;
use Illuminate\Support\Facades\Auth;

class NilaiCpmkController extends Controller
{
    /**
     * Tampilkan form input nilai CPMK untuk 1 kelas (semua mahasiswa x semua CPMK).
     */
    public function create(Kelas $kelas)
    {
        $kelas->load('mataKuliah.cpmk', 'mataKuliah.kurikulum');
    
        // Ambil mahasiswa yang pernah dapat nilai di kelas ini, ATAU sesuai prodi mata kuliah
        $mahasiswaList = \App\Models\Mahasiswa::where('prodi_id', $kelas->mataKuliah->kurikulum->prodi_id)
            ->orderBy('nama')
            ->get();

        $cpmkList = $kelas->mataKuliah->cpmk;

        // Ambil nilai yang sudah ada, disusun jadi [mahasiswa_id][cpmk_id] => nilai
        $nilaiExisting = NilaiCpmk::where('kelas_id', $kelas->id)->get()
            ->groupBy('mahasiswa_id')
            ->map(fn ($items) => $items->keyBy('cpmk_id'));

        return view('nilai-cpmk.create', compact('kelas', 'mahasiswaList', 'cpmkList', 'nilaiExisting'));
    }

    /**
     * Simpan/update nilai CPMK secara massal.
     */
    public function store(StoreNilaiCpmkRequest $request)
    {
        $kelasId = $request->kelas_id;
        $dosenId = Auth::user()->dosen->id;

        // Struktur input: nilai[mahasiswa_id][cpmk_id] = angka
        foreach ($request->nilai as $mahasiswaId => $cpmkNilaiList) {
            foreach ($cpmkNilaiList as $cpmkId => $nilai) {
                if ($nilai === null || $nilai === '') {
                    continue;
                }

                NilaiCpmk::updateOrCreate(
                    [
                        'mahasiswa_id' => $mahasiswaId,
                        'kelas_id' => $kelasId,
                        'cpmk_id' => $cpmkId,
                    ],
                    [
                        'nilai' => $nilai,
                        'dicatat_oleh' => Auth::id(),
                        'dicatat_pada' => now(),
                    ]
                );
            }
        }

        return redirect()->route('mata-kuliah.show', \App\Models\Kelas::find($kelasId)->mata_kuliah_id)
            ->with('status', 'Nilai CPMK berhasil disimpan.');
    }
}