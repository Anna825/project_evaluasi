<?php

namespace App\Exports;

use App\Models\KelasMahasiswa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MahasiswaExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $user = Auth::user();
        $isAdmin = $user->roles()->where('nama_role', 'admin')->exists();

        $kelasQuery = KelasMahasiswa::with('prodi')
            ->whereHas('mahasiswa');

        if (! $isAdmin) {
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;
            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $kelasQuery->where('prodi_id', $prodiId);
        }

        return $kelasQuery
            ->orderBy('prodi_id')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get()
            ->map(fn ($kelas) => new MahasiswaPerKelasSheet($kelas))
            ->all();
    }
}
