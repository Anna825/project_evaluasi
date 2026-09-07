<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTahunAkademikRequest;
use App\Models\TahunAkademik;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahunAkademik = TahunAkademik::latest()->get();

        return view('tahun-akademik.index', compact('tahunAkademik'));
    }

    public function create()
    {
        return view('tahun-akademik.create');
    }

    public function store(StoreTahunAkademikRequest $request)
    {
        TahunAkademik::create($request->validated());

        return redirect()->route('tahun-akademik.index')->with('status', 'Tahun akademik berhasil ditambahkan.');
    }

    public function edit(TahunAkademik $tahunAkademik)
    {
        return view('tahun-akademik.edit', compact('tahunAkademik'));
    }

    public function update(StoreTahunAkademikRequest $request, TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->update($request->validated());

        return redirect()->route('tahun-akademik.index')->with('status', 'Tahun akademik berhasil diperbarui.');
    }

    public function destroy(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->delete();

        return redirect()->route('tahun-akademik.index')->with('status', 'Tahun akademik berhasil dihapus.');
    }
}