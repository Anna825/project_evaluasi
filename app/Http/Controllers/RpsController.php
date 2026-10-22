<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRpsRequest;
use App\Models\Rps;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;

class RpsController extends Controller
{    
    private function pastikanPemilikRps(Rps $rps): void
    {
        $user = Auth::user();

        if (! $user->roles()->where('nama_role', 'dosen')->exists()) {
            abort(403, 'Hanya Dosen yang dapat mengubah RPS.');
        }

        $dosen = $user->dosen;

        if (! $dosen || (int) $rps->dosen_id !== (int) $dosen->id) {
            abort(403, 'Anda hanya dapat mengubah RPS yang Anda buat sendiri.');
        }
    }

    public function create(MataKuliah $mataKuliah)
    {
        $user = Auth::user();
        // Pastikan hanya Dosen yang dapat membuat RPS.
        if (! $user->roles()->where('nama_role', 'dosen')->exists()) {
            abort(403, 'Hanya Dosen yang dapat membuat RPS.');
        }
        return view('rps.create', compact('mataKuliah'));
    }
    
    public function store(StoreRpsRequest $request)
    {
        $user = Auth::user();
        // Pastikan hanya Dosen yang dapat membuat RPS.
        if (! $user->roles()->where('nama_role', 'dosen')->exists()) {
            abort(403, 'Hanya Dosen yang dapat membuat RPS.');
        }
        $data = $request->validated();
        $mataKuliah = MataKuliah::findOrFail($data['mata_kuliah_id']);
        $dosen = $user->dosen;
        if (! $dosen) {
            abort(403, 'Akun Anda belum terhubung ke data dosen.');
        }
        $data['dosen_id'] = $dosen->id;
        $rps = Rps::create($data);
        return redirect()
            ->route('mata-kuliah.show', $rps->mata_kuliah_id)
            ->with('status', 'RPS berhasil ditambahkan.');
    }

    public function edit(Rps $rps)
    {
        $this->pastikanPemilikRps($rps);

        return view('rps.edit', compact('rps'));
    }

    public function update(StoreRpsRequest $request, Rps $rps)
    {
        $this->pastikanPemilikRps($rps);

        $rps->update($request->validated());

        return redirect()
            ->route('mata-kuliah.show', $rps->mata_kuliah_id)
            ->with('status', 'RPS berhasil diperbarui.');
    }

    public function destroy(Rps $rps)
    {
        $this->pastikanPemilikRps($rps);

        $mataKuliahId = $rps->mata_kuliah_id;

        $rps->delete();

        return redirect()
            ->route('mata-kuliah.show', $mataKuliahId)
            ->with('status', 'RPS berhasil dihapus.');
    }
}