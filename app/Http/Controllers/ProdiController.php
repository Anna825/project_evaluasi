<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdiRequest;
use App\Models\Jurusan;
use App\Models\Prodi;

class ProdiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::with('jurusan')->latest()->get();

        return view('prodi.index', compact('prodi'));
    }

    public function create()
    {
        $jurusanList = Jurusan::all();

        return view('prodi.create', compact('jurusanList'));
    }

    public function store(StoreProdiRequest $request)
    {
        Prodi::create($request->validated());

        return redirect()->route('prodi.index')->with('status', 'Prodi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        $jurusanList = Jurusan::all();

        return view('prodi.edit', compact('prodi', 'jurusanList'));
    }

    public function update(StoreProdiRequest $request, Prodi $prodi)
    {
        $prodi->update($request->validated());

        return redirect()->route('prodi.index')->with('status', 'Prodi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('prodi.index')->with('status', 'Prodi berhasil dihapus.');
    }
}