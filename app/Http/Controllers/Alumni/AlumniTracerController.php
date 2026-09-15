<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicMahasiswaController;
use Illuminate\Http\Request;

class AlumniTracerController extends Controller
{
    /**
     * Ambil NIM mahasiswa yang terhubung dengan akun Alumni.
     */
    private function getAlumniNim(): string
    {
        $user = auth()->user();

        $userRole = $user->userRoles()
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'alumni');
            })
            ->with('mahasiswa')
            ->first();

        if (! $userRole || ! $userRole->mahasiswa) {
            abort(403, 'Data Alumni belum terhubung dengan data mahasiswa.');
        }

        if ($userRole->mahasiswa->status !== 'lulus') {
            abort(403, 'Akun Alumni hanya dapat digunakan oleh mahasiswa yang sudah lulus.');
        }

        return $userRole->mahasiswa->nim;
    }

    /**
     * Tampilkan Tracer Study Alumni.
     */
    public function create()
    {
        $nim = $this->getAlumniNim();

        return app(PublicMahasiswaController::class)
            ->tracerCreate($nim);
    }

    /**
     * Simpan Tracer Study Alumni.
     */
    public function store(Request $request)
    {
        $nim = $this->getAlumniNim();

        return app(PublicMahasiswaController::class)
            ->tracerStore($request, $nim);
    }
}