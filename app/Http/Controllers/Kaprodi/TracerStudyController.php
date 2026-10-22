<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    private function getProdiId(): int
    {
        $user = auth()->user();

        $prodiId = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot?->prodi_id;

        if (! $prodiId) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        return (int) $prodiId;
    }

    public function index(Request $request)
    {
        $prodiId = $this->getProdiId();
        $prodi = Prodi::findOrFail($prodiId);

        $baseQuery = TracerStudy::query()
            ->where('prodi_id', $prodiId);

        $totalAlumni = $prodi->mahasiswa()
            ->where('status', 'lulus')
            ->count();

        $totalAlumniTerisi = (clone $baseQuery)
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');

        $persentaseRespons = $totalAlumni > 0
            ? round(($totalAlumniTerisi / $totalAlumni) * 100, 1)
            : 0;

        $tahunLulusList = (clone $baseQuery)
            ->whereNotNull('tahun_lulus')
            ->distinct()
            ->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        $periodeList = (clone $baseQuery)
            ->whereNotNull('periode_pelacakan')
            ->distinct()
            ->orderByDesc('periode_pelacakan')
            ->pluck('periode_pelacakan');

        $query = (clone $baseQuery)
            ->with('mahasiswa.prodi')
            ->latest('tanggal_pengisian');

        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->integer('tahun_lulus'));
        }

        if ($request->filled('periode_pelacakan')) {
            $query->where('periode_pelacakan', $request->input('periode_pelacakan'));
        }

        if ($request->filled('jenis_pengisian')) {
            $query->where('jenis_pengisian', $request->input('jenis_pengisian'));
        }

        $tracerList = $query->paginate(10)->withQueryString();

        return view('kaprodi.tracer-study.index', compact(
            'prodi',
            'tracerList',
            'totalAlumni',
            'totalAlumniTerisi',
            'persentaseRespons',
            'tahunLulusList',
            'periodeList'
        ));
    }

    public function show(TracerStudy $tracerStudy)
    {
        $prodiId = $this->getProdiId();

        if ((int) $tracerStudy->prodi_id !== $prodiId) {
            abort(403, 'Anda tidak memiliki akses ke data Tracer Study ini.');
        }

        $tracerStudy->load([
            'mahasiswa.prodi',
            'prodi',
        ]);

        return view('kaprodi.tracer-study.show', compact('tracerStudy'));
    }
}
