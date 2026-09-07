<?php

namespace App\Http\Controllers;

use App\Models\LaporanAkhirPenelitianPkm;
use App\Models\PenelitianPkm;
use Illuminate\Http\Request;

class LaporanAkhirPkmController extends Controller
{
    public function store(Request $request, PenelitianPkm $penelitianPkm)
    {
        $validated = $request->validate([
            'link_laporan' => ['required', 'url'],
            'tanggal_upload' => ['required', 'date'],
        ]);

        $validated['penelitian_pkm_id'] = $penelitianPkm->id;

        LaporanAkhirPenelitianPkm::updateOrCreate(
            ['penelitian_pkm_id' => $penelitianPkm->id],
            $validated
        );

        $penelitianPkm->update(['status' => 'selesai']);

        return redirect()->route('penelitian-pkm.show', $penelitianPkm)->with('status', 'Laporan akhir berhasil disimpan.');
    }
}