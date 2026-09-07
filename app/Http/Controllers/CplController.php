<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCplRequest;
use App\Models\Cpl;
use App\Models\Kurikulum;

class CplController extends Controller
{
    public function create(Kurikulum $kurikulum)
    {
        return view('cpl.create', compact('kurikulum'));
    }

    public function store(StoreCplRequest $request)
    {
        $cpl = Cpl::create($request->validated());

        return redirect()->route('kurikulum.show', $cpl->kurikulum_id)->with('status', 'CPL berhasil ditambahkan.');
    }

    public function edit(Cpl $cpl)
    {
        return view('cpl.edit', compact('cpl'));
    }

    public function update(StoreCplRequest $request, Cpl $cpl)
    {
        $cpl->update($request->validated());

        return redirect()->route('kurikulum.show', $cpl->kurikulum_id)->with('status', 'CPL berhasil diperbarui.');
    }

    public function destroy(Cpl $cpl)
    {
        $kurikulumId = $cpl->kurikulum_id;
        $cpl->delete();

        return redirect()->route('kurikulum.show', $kurikulumId)->with('status', 'CPL berhasil dihapus.');
    }
}