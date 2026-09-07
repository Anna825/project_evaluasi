<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiDosenController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $prestasiList = $dosen->prestasi()->with('tahunAkademik')->latest()->get();

        return view('prestasi.dosen-index', compact('prestasiList'));
    }

    public function create()
    {
        $tahunAkademikList = TahunAkademik::all();

        return view('prestasi.dosen-create', compact('tahunAkademikList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tingkat' => ['nullable', 'string', 'max:255'],
            'jenis' => ['nullable', 'string', 'max:255'],
            'peringkat' => ['nullable', 'string', 'max:255'],
            'tahun_akademik_id' => ['required', 'exists:tahun_akademik,id'],
        ]);

        $prestasi = Prestasi::create($validated);
        $prestasi->dosen()->attach(Auth::user()->dosen->id);

        return redirect()->route('prestasi-dosen.index')->with('status', 'Prestasi berhasil ditambahkan.');
    }
}