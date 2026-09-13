<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKurikulumRequest;
use App\Models\Kurikulum;
use App\Models\Prodi;

class KurikulumController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin boleh melihat semua kurikulum.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            $kurikulum = Kurikulum::with('prodi')
                ->latest()
                ->get();
        } else {
            // Kaprodi hanya boleh melihat kurikulum Prodi-nya sendiri.
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $kurikulum = Kurikulum::with('prodi')
                ->where('prodi_id', $prodiId)
                ->latest()
                ->get();
        }

        return view('kurikulum.index', compact('kurikulum'));
    }

    public function create()
    {
        $user = auth()->user();

        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            $prodiList = Prodi::all();
        } else {
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $prodiList = Prodi::where('id', $prodiId)->get();
        }

        return view('kurikulum.create', compact('prodiList'));
    }

    public function store(StoreKurikulumRequest $request)
    {
        $user = auth()->user();

        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        $data = $request->validated();

        if (! $isAdmin) {
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            // Jangan izinkan Kaprodi membuat kurikulum
            // untuk Prodi lain melalui request manual.
            if ((int) $data['prodi_id'] !== (int) $prodiId) {
                abort(403, 'Anda tidak dapat menambahkan kurikulum untuk Program Studi lain.');
            }
        }

        Kurikulum::create($data);

        return redirect()
            ->route('kurikulum.index')
            ->with('status', 'Kurikulum berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum)
    {
        $this->authorizeKurikulumAccess($kurikulum);

        $kurikulum->load('prodi', 'cpl', 'mataKuliah');

        return view('kurikulum.show', compact('kurikulum'));
    }

    public function edit(Kurikulum $kurikulum)
    {
        $this->authorizeKurikulumAccess($kurikulum);

        $user = auth()->user();

        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            $prodiList = Prodi::all();
        } else {
            $prodiList = Prodi::where('id', $kurikulum->prodi_id)->get();
        }

        return view('kurikulum.edit', compact('kurikulum', 'prodiList'));
    }

    public function update(StoreKurikulumRequest $request, Kurikulum $kurikulum)
    {
        $this->authorizeKurikulumAccess($kurikulum);

        $user = auth()->user();

        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        $data = $request->validated();

        if (! $isAdmin) {
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            // Cegah Kaprodi memindahkan kurikulum
            // ke Prodi lain melalui form edit.
            if ((int) $data['prodi_id'] !== (int) $prodiId) {
                abort(403, 'Anda tidak dapat memindahkan kurikulum ke Program Studi lain.');
            }
        }

        $kurikulum->update($data);

        return redirect()
            ->route('kurikulum.index')
            ->with('status', 'Kurikulum berhasil diperbarui.');
    }

    public function destroy(Kurikulum $kurikulum)
    {
        $this->authorizeKurikulumAccess($kurikulum);

        $kurikulum->delete();

        return redirect()
            ->route('kurikulum.index')
            ->with('status', 'Kurikulum berhasil dihapus.');
    }

    /**
     * Memastikan Kurikulum hanya dapat diakses
     * oleh Admin atau Kaprodi dari Prodi yang sama.
     */
    private function authorizeKurikulumAccess(Kurikulum $kurikulum): void
    {
        $user = auth()->user();

        // Admin memiliki akses penuh.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            return;
        }

        // Ambil Prodi dari role Kaprodi.
        $kaprodiPivot = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot;

        $prodiId = $kaprodiPivot?->prodi_id;

        if (! $prodiId) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        // Dinding pemisah antar Prodi.
        if ((int) $kurikulum->prodi_id !== (int) $prodiId) {
            abort(403, 'Anda tidak memiliki akses ke Kurikulum Program Studi lain.');
        }
    }
}