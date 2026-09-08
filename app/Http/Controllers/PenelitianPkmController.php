<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenelitianPkmRequest;
use App\Models\PenelitianPkm;
use App\Models\TahunAkademik;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;

class PenelitianPkmController extends Controller
{
    /**
     * Pastikan dosen yang login termasuk anggota tim penelitian ini.
     */
    private function pastikanAnggotaTim(PenelitianPkm $penelitianPkm): void
    {
        $dosenId = Auth::user()->dosen->id;
        $termasuk = $penelitianPkm->dosen()->where('dosen_id', $dosenId)->exists();

        if (! $termasuk) {
            abort(403, 'Anda bukan anggota tim penelitian/PKM ini.');
        }
    }

    /**
     * Daftar Penelitian/PKM milik dosen yang login.
     */
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $penelitianList = $dosen->penelitianPkm()->with('laporanAkhir', 'hilirisasi')->latest()->get();

        return view('penelitian-pkm.index', compact('penelitianList'));
    }

    public function create()
    {
        $tahunAkademikList = TahunAkademik::all();
        $dosenList = Dosen::where('id', '!=', Auth::user()->dosen->id)->get();

        return view('penelitian-pkm.create', compact('tahunAkademikList', 'dosenList'));
    }

    public function store(StorePenelitianPkmRequest $request)
    {
        $penelitian = PenelitianPkm::create($request->validated() + ['status' => 'diajukan']);

        // Dosen pengaju otomatis jadi "Ketua"
        $penelitian->dosen()->attach(Auth::user()->dosen->id, ['peran' => 'Ketua']);

        // Anggota tambahan (kalau dipilih)
        if ($request->has('anggota')) {
            foreach ($request->anggota as $dosenId) {
                $penelitian->dosen()->attach($dosenId, ['peran' => 'Anggota']);
            }
        }

        return redirect()->route('penelitian-pkm.index')->with('status', 'Penelitian/PKM berhasil diajukan.');
    }

    public function show(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAnggotaTim($penelitianPkm);

        $penelitianPkm->load('dosen', 'laporanAkhir', 'hilirisasi', 'tahunAkademik');

        return view('penelitian-pkm.show', compact('penelitianPkm'));
    }

    public function edit(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAnggotaTim($penelitianPkm);

        $tahunAkademikList = TahunAkademik::all();

        return view('penelitian-pkm.edit', compact('penelitianPkm', 'tahunAkademikList'));
    }

    public function update(StorePenelitianPkmRequest $request, PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAnggotaTim($penelitianPkm);

        $penelitianPkm->update($request->validated());

        return redirect()->route('penelitian-pkm.index')->with('status', 'Penelitian/PKM berhasil diperbarui.');
    }

    public function destroy(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAnggotaTim($penelitianPkm);

        $penelitianPkm->delete();

        return redirect()->route('penelitian-pkm.index')->with('status', 'Penelitian/PKM berhasil dihapus.');
    }

    /**
     * Kaprodi: lihat semua Penelitian/PKM untuk diverifikasi.
     */
    public function verifikasiIndex()
    {
        $penelitianList = PenelitianPkm::with('dosen', 'tahunAkademik')->latest()->get();

        return view('penelitian-pkm.verifikasi', compact('penelitianList'));
    }

    public function verifikasiShow(PenelitianPkm $penelitianPkm)
    {
        $penelitianPkm->load(
            'dosen',
            'tahunAkademik',
            'laporanAkhir',
            'hilirisasi'
        );

        return view('penelitian-pkm.verifikasi-show', compact('penelitianPkm'));
    }

    /**
     * Kaprodi: setujui Penelitian/PKM.
     */
    public function verifikasiApprove(PenelitianPkm $penelitianPkm)
    {
        $penelitianPkm->update(['status' => 'disetujui']);

        return back()->with('status', 'Penelitian/PKM telah disetujui.');
    }

    /**
     * Kaprodi: tolak Penelitian/PKM.
     */
    public function verifikasiReject(PenelitianPkm $penelitianPkm)
    {
        $penelitianPkm->update(['status' => 'ditolak']);

        return back()->with('status', 'Penelitian/PKM telah ditolak.');
    }
}