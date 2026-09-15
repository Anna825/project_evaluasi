<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AlumniDashboardController extends Controller
{
    /**
     * Dashboard Alumni.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil data mahasiswa yang terhubung dengan akun Alumni
        $userRole = $user->userRoles()
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'alumni');
            })
            ->with('mahasiswa.prodi')
            ->first();

        if (! $userRole || ! $userRole->mahasiswa) {
            abort(403, 'Data Alumni belum terhubung dengan data mahasiswa.');
        }

        $mahasiswa = $userRole->mahasiswa;

        // Ambil seluruh riwayat Tracer Study Alumni
        $tracerStudies = $mahasiswa->tracerStudy()
            ->latest('created_at')
            ->get();

        $tracerCount = $tracerStudies->count();

        // Data pengisian terakhir
        $tracerTerakhir = $tracerStudies->first();

        return view('alumni.dashboard', compact(
            'mahasiswa',
            'tracerStudies',
            'tracerCount',
            'tracerTerakhir'
        ));
    }
}