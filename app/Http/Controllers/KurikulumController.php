<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKurikulumRequest;
use App\Models\Kurikulum;
use App\Models\Prodi;

class KurikulumController extends Controller
{
    public function index()
    {
        $kurikulum = Kurikulum::with('prodi')->latest()->get();

        return view('kurikulum.index', compact('kurikulum'));
    }

    public function create()
    {
        $prodiList = Prodi::all();

        return view('kurikulum.create', compact('prodiList'));
    }

    public function store(StoreKurikulumRequest $request)
    {
        Kurikulum::create($request->validated());

        return redirect()->route('kurikulum.index')->with('status', 'Kurikulum berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum)
    {
        $kurikulum->load('prodi', 'cpl', 'mataKuliah');

        return view('kurikulum.show', compact('kurikulum'));
    }

    public function edit(Kurikulum $kurikulum)
    {
        $prodiList = Prodi::all();

        return view('kurikulum.edit', compact('kurikulum', 'prodiList'));
    }

    public function update(StoreKurikulumRequest $request, Kurikulum $kurikulum)
    {
        $kurikulum->update($request->validated());

        return redirect()->route('kurikulum.index')->with('status', 'Kurikulum berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum)
    {
        $kurikulum->delete();

        return redirect()->route('kurikulum.index')->with('status', 'Kurikulum berhasil dihapus.');
    }
}