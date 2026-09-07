<?php

namespace App\Http\Controllers;

use App\Models\HilirisasiPkm;
use App\Models\PenelitianPkm;
use Illuminate\Http\Request;

class HilirisasiPkmController extends Controller
{
    public function store(Request $request, PenelitianPkm $penelitianPkm)
    {
        $validated = $request->validate([
            'bentuk_luaran' => ['required', 'string', 'max:255'],
            'status_hilirisasi' => ['required', 'string', 'max:255'],
        ]);

        $validated['penelitian_pkm_id'] = $penelitianPkm->id;

        HilirisasiPkm::updateOrCreate(
            ['penelitian_pkm_id' => $penelitianPkm->id],
            $validated
        );

        return redirect()->route('penelitian-pkm.show', $penelitianPkm)->with('status', 'Data hilirisasi berhasil disimpan.');
    }
}