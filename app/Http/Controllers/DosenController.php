<?php

namespace App\Http\Controllers;

use App\Models\Dosen;

class DosenController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $kaprodiPivot = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot;

        $prodiId = $kaprodiPivot?->prodi_id;

        $dosen = Dosen::where('prodi_id', $prodiId)
            ->latest()
            ->get();

        return view('dosen.index', compact('dosen'));
    }

    public function show(Dosen $dosen)
    {
        $dosen->load('prodi');

        return view('dosen.show', compact('dosen'));
    }
}