<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCpmkRequest;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;

class CpmkController extends Controller
{
    /**
     * Pastikan mata kuliah ini benar-benar diampu oleh dosen yang login.
     */
    private function pastikanDiampu(MataKuliah $mataKuliah): void
    {
        $user = Auth::user();

        if (! $user->dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        /*
        * Jika user adalah Kaprodi,
        * akses berdasarkan Program Studi Kaprodi.
        */
        $isKaprodi = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->exists();

        if ($isKaprodi) {
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $mataKuliah->loadMissing('kurikulum');

            if (
                ! $mataKuliah->kurikulum ||
                (int) $mataKuliah->kurikulum->prodi_id !== (int) $prodiId
            ) {
                abort(403, 'Anda tidak memiliki akses ke CPMK Program Studi lain.');
            }

            return;
        }

        /*
        * Jika user adalah Dosen biasa,
        * tetap gunakan aturan lama:
        * hanya boleh mengakses mata kuliah yang diampu.
        */
        $dosenId = $user->dosen->id;

        $diampu = $mataKuliah->kelas()
            ->where('dosen_pengampu_id', $dosenId)
            ->exists();

        if (! $diampu) {
            abort(403, 'Anda tidak mengampu mata kuliah ini.');
        }
    }

    public function create(MataKuliah $mataKuliah)
    {
        $this->pastikanDiampu($mataKuliah);

        return view('cpmk.create', compact('mataKuliah'));
    }

    public function store(StoreCpmkRequest $request)
    {
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);
        $this->pastikanDiampu($mataKuliah);

        $cpmk = Cpmk::create($request->validated());

        return redirect()->route('mata-kuliah.show', $cpmk->mata_kuliah_id)->with('status', 'CPMK berhasil ditambahkan.');
    }

    public function edit(Cpmk $cpmk)
    {
        $this->pastikanDiampu($cpmk->mataKuliah);

        return view('cpmk.edit', compact('cpmk'));
    }

    public function update(StoreCpmkRequest $request, Cpmk $cpmk)
    {
        $this->pastikanDiampu($cpmk->mataKuliah);

        $cpmk->update($request->validated());

        return redirect()->route('mata-kuliah.show', $cpmk->mata_kuliah_id)->with('status', 'CPMK berhasil diperbarui.');
    }

    public function destroy(Cpmk $cpmk)
    {
        $this->pastikanDiampu($cpmk->mataKuliah);

        $mataKuliahId = $cpmk->mata_kuliah_id;
        $cpmk->delete();

        return redirect()->route('mata-kuliah.show', $mataKuliahId)->with('status', 'CPMK berhasil dihapus.');
    }
}