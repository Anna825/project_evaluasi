<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRpsRequest;
use App\Models\Rps;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;

class RpsController extends Controller
{
    /**
     * Pastikan mata kuliah ini benar-benar diampu oleh dosen yang login.
     */
    private function pastikanDiampu(MataKuliah $mataKuliah): void
    {
        $dosenId = Auth::user()->dosen->id;
        $diampu = $mataKuliah->kelas()->where('dosen_pengampu_id', $dosenId)->exists();

        if (! $diampu) {
            abort(403, 'Anda tidak mengampu mata kuliah ini.');
        }
    }

    public function create(MataKuliah $mataKuliah)
    {
        $this->pastikanDiampu($mataKuliah);

        return view('rps.create', compact('mataKuliah'));
    }

    public function store(StoreRpsRequest $request)
    {
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);
        $this->pastikanDiampu($mataKuliah);

        $data = $request->validated();
        $data['dosen_id'] = Auth::user()->dosen->id;

        $rps = Rps::create($data);

        return redirect()->route('mata-kuliah.show', $rps->mata_kuliah_id)->with('status', 'RPS berhasil ditambahkan.');
    }

    public function edit(Rps $rps)
    {
        $this->pastikanDiampu($rps->mataKuliah);

        return view('rps.edit', compact('rps'));
    }

    public function update(StoreRpsRequest $request, Rps $rps)
    {
        $this->pastikanDiampu($rps->mataKuliah);

        $rps->update($request->validated());

        return redirect()->route('mata-kuliah.show', $rps->mata_kuliah_id)->with('status', 'RPS berhasil diperbarui.');
    }

    public function destroy(Rps $rps)
    {
        $this->pastikanDiampu($rps->mataKuliah);

        $mataKuliahId = $rps->mata_kuliah_id;
        $rps->delete();

        return redirect()->route('mata-kuliah.show', $mataKuliahId)->with('status', 'RPS berhasil dihapus.');
    }
}