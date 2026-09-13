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

        // Kaprodi wajib memiliki Prodi
        if (! $prodiId) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        // Hanya tampilkan Dosen dari Prodi Kaprodi
        $dosen = Dosen::where('prodi_id', $prodiId)
            ->latest()
            ->get();

        return view('dosen.index', compact('dosen'));
    }

    public function show(Dosen $dosen)
    {
        $user = auth()->user();

        $kaprodiPivot = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot;

        $prodiId = $kaprodiPivot?->prodi_id;

        // Kaprodi wajib memiliki Prodi
        if (! $prodiId) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        // Dinding Prodi:
        // Kaprodi hanya boleh melihat Dosen dari Prodinya sendiri
        if ((int) $dosen->prodi_id !== (int) $prodiId) {
            abort(403, 'Anda tidak memiliki akses ke data Dosen dari Program Studi lain.');
        }

        $dosen->load('prodi');

        return view('dosen.show', compact('dosen'));
    }
}