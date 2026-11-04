<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCpmkRequest;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CpmkController extends Controller
{
    /**
     * Pastikan mata kuliah ini benar-benar diampu oleh dosen yang login.
     */
    
    private function pastikanPembuat(Cpmk $cpmk): void
    {
        $user = Auth::user();

        // Kaprodi hanya boleh melihat CPMK, bukan mengedit atau menghapus.
        if ($user->roles()->where('nama_role', 'kaprodi')->exists()) {
            abort(403, 'Kaprodi hanya dapat melihat CPMK dan pemetaan CPL.');
        }

        // Hanya Dosen yang membuat CPMK yang boleh mengubahnya.
        if (
            ! $user->roles()->where('nama_role', 'dosen')->exists()
            || (int) $cpmk->created_by !== (int) $user->id
        ) {
            abort(403, 'Anda hanya dapat mengubah CPMK yang Anda buat sendiri.');
        }
    }

    public function create()
    {
        $mataKuliahList = MataKuliah::with('kurikulum.cpl')
            ->orderBy('nama')
            ->get();

        $cplList = collect();

        return view('cpmk.create', compact(
            'mataKuliahList',
            'cplList'
        ));
    }

    public function store(StoreCpmkRequest $request)
    {
        $data = $request->validated();

        $mataKuliah = MataKuliah::with('kurikulum.cpl')
            ->findOrFail($data['mata_kuliah_id']);

        $cplIds = collect($data['cpl_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        // Pastikan semua CPL berasal dari kurikulum
        // mata kuliah yang dipilih.
        $cplValidIds = $mataKuliah->kurikulum
            ? $mataKuliah->kurikulum->cpl()
                ->whereIn('cpl.id', $cplIds)
                ->pluck('cpl.id')
                ->map(fn ($id) => (int) $id)
            : collect();

        if ($cplValidIds->count() !== $cplIds->count()) {
            return back()
                ->withInput()
                ->withErrors([
                    'cpl_ids' => 'CPL harus berasal dari kurikulum mata kuliah yang dipilih.',
                ]);
        }

        // Pastikan bobot tersedia untuk setiap CPL terpilih.
        foreach ($cplIds as $cplId) {
            if (! isset($data['bobot'][$cplId])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'bobot' => 'Bobot wajib diisi untuk setiap CPL yang dipilih.',
                    ]);
            }
        }

        DB::transaction(function () use ($data, $mataKuliah, $cplIds) {
            $cpmk = Cpmk::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'kode' => $data['kode'],
                'deskripsi' => $data['deskripsi'],
                'created_by' => Auth::id(),
            ]);
            $pemetaan = [];

            foreach ($cplIds as $cplId) {
                $pemetaan[$cplId] = [
                    'bobot' => $data['bobot'][$cplId],
                ];
            }

            $cpmk->cpl()->sync($pemetaan);
        });

        return redirect()
            ->route('mata-kuliah.show', $mataKuliah)
            ->with('status', 'CPMK dan pemetaan CPL berhasil disimpan.');
    }

    public function edit(Cpmk $cpmk)
    {
        $this->pastikanPembuat($cpmk);

        return view('cpmk.edit', compact('cpmk'));
    }

    public function update(StoreCpmkRequest $request, Cpmk $cpmk)
    {
        $this->pastikanPembuat($cpmk);

        $data = $request->validated();
        $mataKuliah = MataKuliah::with('kurikulum.cpl')->findOrFail($cpmk->mata_kuliah_id);
        $cplIds = collect($data['cpl_ids'])->map(fn ($id) => (int) $id)->unique()->values();
        $validIds = $mataKuliah->kurikulum
            ? $mataKuliah->kurikulum->cpl()->whereIn('cpl.id', $cplIds)->pluck('cpl.id')->map(fn ($id) => (int) $id)
            : collect();

        if ($validIds->count() !== $cplIds->count()) {
            return back()->withInput()->withErrors([
                'cpl_ids' => 'CPL harus berasal dari kurikulum mata kuliah yang dipilih.',
            ]);
        }

        foreach ($cplIds as $cplId) {
            if (! isset($data['bobot'][$cplId])) {
                return back()->withInput()->withErrors([
                    'bobot' => 'Bobot wajib diisi untuk setiap CPL yang dipilih.',
                ]);
            }
        }

        DB::transaction(function () use ($cpmk, $data, $cplIds) {
            $cpmk->update([
                'kode' => $data['kode'],
                'deskripsi' => $data['deskripsi'],
            ]);
            $mapping = [];
            foreach ($cplIds as $cplId) {
                $mapping[$cplId] = ['bobot' => $data['bobot'][$cplId]];
            }
            $cpmk->cpl()->sync($mapping);
        });

        return redirect()->route('mata-kuliah.show', $cpmk->mata_kuliah_id)->with('status', 'CPMK dan pemetaan CPL berhasil diperbarui.');
    }

    public function destroy(Cpmk $cpmk)
    {
        $this->pastikanPembuat($cpmk);

        $mataKuliahId = $cpmk->mata_kuliah_id;
        $cpmk->delete();

        return redirect()->route('mata-kuliah.show', $mataKuliahId)->with('status', 'CPMK berhasil dihapus.');
    }
}