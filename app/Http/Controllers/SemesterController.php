<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesterList = Semester::with('tahunAkademik')
            ->latest()
            ->get();

        return view('semester.index', compact('semesterList'));
    }

    public function create()
    {
        $tahunAkademikList = TahunAkademik::latest()->get();

        return view('semester.create', compact('tahunAkademikList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_akademik_id' => [
                'required',
                'exists:tahun_akademik,id',
            ],
            'jenis' => [
                'required',
                'in:ganjil,genap',
            ],
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after:tanggal_mulai',
            ],
        ]);

        Semester::create($validated);

        return redirect()
            ->route('semester.index')
            ->with('status', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester)
    {
        $tahunAkademikList = TahunAkademik::latest()->get();

        return view(
            'semester.edit',
            compact('semester', 'tahunAkademikList')
        );
    }

    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'tahun_akademik_id' => [
                'required',
                'exists:tahun_akademik,id',
            ],
            'jenis' => [
                'required',
                'in:ganjil,genap',
            ],
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after:tanggal_mulai',
            ],
        ]);

        $semester->update($validated);

        return redirect()
            ->route('semester.index')
            ->with('status', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();

        return redirect()
            ->route('semester.index')
            ->with('status', 'Semester berhasil dihapus.');
    }
}