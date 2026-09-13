<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\DosenJabatanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenProfileController extends Controller
{
    public function show()
    {
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        $dosen->load('prodi');

        return view('dosen.profil.show', compact('dosen'));
    }

    public function edit()
    {
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        $dosen->load('prodi');

        return view('dosen.profil.edit', compact('dosen'));
    }

    public function update(Request $request)
    {
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        $validated = $request->validate([
            'jabatan_fungsional' => ['nullable', 'string', 'max:255'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:255'],
            'institusi_lulusan' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
        ]);

        $jabatanBerubah = $dosen->jabatan_fungsional !== $validated['jabatan_fungsional'];

        $dosen->update($validated);

        if ($jabatanBerubah && ! empty($validated['jabatan_fungsional'])) {
            DosenJabatanLog::create([
                'dosen_id' => $dosen->id,
                'jabatan_fungsional' => $validated['jabatan_fungsional'],
                'tanggal_mulai' => now()->toDateString(),
            ]);
        }

        return redirect()
            ->route('dosen.profil.show')
            ->with('status', 'Profil Dosen berhasil diperbarui.');
    }
}