<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCplRequest;
use App\Models\Cpl;
use App\Models\Kurikulum;

class CplController extends Controller
{
    public function create(Kurikulum $kurikulum)
    {
        $this->authorizeKurikulumAccess($kurikulum);

        return view('cpl.create', compact('kurikulum'));
    }

    public function store(StoreCplRequest $request)
    {
        $data = $request->validated();

        $kurikulum = Kurikulum::findOrFail($data['kurikulum_id']);

        $this->authorizeKurikulumAccess($kurikulum);

        $cpl = Cpl::create($data);

        return redirect()
            ->route('kurikulum.show', $cpl->kurikulum_id)
            ->with('status', 'CPL berhasil ditambahkan.');
    }

    public function edit(Cpl $cpl)
    {
        $cpl->load('kurikulum');

        $this->authorizeKurikulumAccess($cpl->kurikulum);

        return view('cpl.edit', compact('cpl'));
    }

    public function update(StoreCplRequest $request, Cpl $cpl)
    {
        $cpl->load('kurikulum');

        $this->authorizeKurikulumAccess($cpl->kurikulum);

        $data = $request->validated();

        // Pastikan CPL tidak dipindahkan ke Kurikulum Prodi lain.
        if (
            isset($data['kurikulum_id']) &&
            (int) $data['kurikulum_id'] !== (int) $cpl->kurikulum_id
        ) {
            $newKurikulum = Kurikulum::findOrFail($data['kurikulum_id']);

            $this->authorizeKurikulumAccess($newKurikulum);
        }

        $cpl->update($data);

        return redirect()
            ->route('kurikulum.show', $cpl->kurikulum_id)
            ->with('status', 'CPL berhasil diperbarui.');
    }

    public function destroy(Cpl $cpl)
    {
        $cpl->load('kurikulum');

        $this->authorizeKurikulumAccess($cpl->kurikulum);

        $kurikulumId = $cpl->kurikulum_id;

        $cpl->delete();

        return redirect()
            ->route('kurikulum.show', $kurikulumId)
            ->with('status', 'CPL berhasil dihapus.');
    }

    /**
     * Memastikan Admin memiliki akses penuh,
     * sedangkan Kaprodi hanya dapat mengakses
     * CPL dari Kurikulum Prodi-nya sendiri.
     */
    private function authorizeKurikulumAccess(Kurikulum $kurikulum): void
    {
        $user = auth()->user();

        // Admin boleh mengakses semua Prodi.
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

        // CPL mengikuti Prodi dari Kurikulum.
        if ((int) $kurikulum->prodi_id !== (int) $prodiId) {
            abort(403, 'Anda tidak memiliki akses ke CPL Program Studi lain.');
        }
    }
}