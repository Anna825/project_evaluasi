<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PrestasiExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $user = Auth::user();
        $isAdmin = $user->roles()->where('nama_role', 'admin')->exists();
        $prodiId = null;

        if (! $isAdmin) {
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;
            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }
        }

        return [
            new PrestasiPerJenisSheet('mahasiswa', $prodiId),
            new PrestasiPerJenisSheet('dosen', $prodiId),
        ];
    }
}
