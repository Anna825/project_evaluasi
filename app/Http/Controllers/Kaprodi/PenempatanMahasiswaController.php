<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PenempatanMahasiswaController extends Controller
{
    /**
     * Mengambil prodi berdasarkan role Kaprodi yang sedang login.
     */
    private function prodiKaprodi()
    {
        $user = auth()->user();

        $kaprodiRole = $user->userRoles()
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'kaprodi');
            })
            ->with('prodi')
            ->first();

        if (! $kaprodiRole || ! $kaprodiRole->prodi_id) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        return $kaprodiRole->prodi;
    }

    /**
     * Menampilkan daftar mahasiswa pada prodi Kaprodi.
     */
    public function index(Request $request)
    {
        $prodi = $this->prodiKaprodi();

        $mahasiswaQuery = Mahasiswa::with([
            'kelasMahasiswa',
            'progresStudi',
        ])
            ->where('prodi_id', $prodi->id);

        if ($request->filled('status')) {
            $mahasiswaQuery->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $mahasiswaQuery->where(function ($query) use ($keyword) {
                $query->where('nim', 'like', "%{$keyword}%")
                    ->orWhere('nama', 'like', "%{$keyword}%");
            });
        }

        $mahasiswa = $mahasiswaQuery
            ->orderBy('nim')
            ->paginate(15)
            ->withQueryString();

        return view(
            'kaprodi.penempatan.index',
            compact('prodi', 'mahasiswa')
        );
    }
}