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

        $termasuk = $penelitianPkm->dosen()
            ->where('dosen_id', $dosenId)
            ->exists();

        if (! $termasuk) {
            abort(403, 'Anda bukan anggota tim penelitian/PKM ini.');
        }
    }

    /**
     * Pastikan hanya Ketua yang boleh mengubah
     * atau menghapus Penelitian/PKM.
     */
    private function pastikanKetuaTim(PenelitianPkm $penelitianPkm): void
    {
        $dosenId = Auth::user()->dosen->id;

        $isKetua = $penelitianPkm->dosen()
            ->where('dosen_id', $dosenId)
            ->wherePivot('peran', 'Ketua')
            ->exists();

        if (! $isKetua) {
            abort(403, 'Hanya Ketua penelitian/PKM yang dapat mengubah data tim.');
        }
    }

    /**
     * Pastikan Admin boleh mengakses semua data,
     * sedangkan Kaprodi hanya boleh mengakses
     * Penelitian/PKM yang Ketua-nya berasal dari Prodi sendiri.
     */
    private function pastikanAksesKaprodi(PenelitianPkm $penelitianPkm): void
    {
        $user = Auth::user();

        // Admin memiliki akses penuh.
        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            return;
        }

        // Ambil Prodi dari role Kaprodi.
        $kaprodiPivot = $user->roles()
            ->where('nama_role', 'kaprodi')
            ->first()?->pivot;

        $prodiId = $kaprodiPivot?->prodi_id;

        if (! $prodiId) {
            abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
        }

        // Penelitian/PKM dianggap milik Prodi
        // berdasarkan Dosen yang berperan sebagai Ketua.
        $dosenDariProdiIni = $penelitianPkm->dosen()
            ->where('dosen.prodi_id', $prodiId)
            ->exists();

        if (! $dosenDariProdiIni) {
            abort(403, 'Anda tidak memiliki akses ke Penelitian/PKM yang tidak memiliki Dosen dari Program Studi Anda.');
        }
    }

    /**
     * Daftar Penelitian/PKM milik dosen yang login.
     */
    public function index()
    {
        $dosen = Auth::user()->dosen;

        $penelitianList = $dosen->penelitianPkm()
            ->with('laporanAkhir', 'hilirisasi')
            ->latest()
            ->get();

        return view('penelitian-pkm.index', compact('penelitianList'));
    }

    public function create()
    {
        $tahunAkademikList = TahunAkademik::all();

        $dosenList = Dosen::where(
            'id',
            '!=',
            Auth::user()->dosen->id
        )->get();

        return view(
            'penelitian-pkm.create',
            compact('tahunAkademikList', 'dosenList')
        );
    }

    public function store(StorePenelitianPkmRequest $request)
    {
        $penelitian = PenelitianPkm::create(
            $request->validated() + ['status' => 'diajukan']
        );

        // Dosen pengaju otomatis menjadi Ketua.
        $penelitian->dosen()->attach(
            Auth::user()->dosen->id,
            ['peran' => 'Ketua']
        );

        // Anggota tambahan jika dipilih.
        if ($request->has('anggota')) {
            foreach ($request->anggota as $dosenId) {
                $penelitian->dosen()->attach(
                    $dosenId,
                    ['peran' => 'Anggota']
                );
            }
        }

        return redirect()
            ->route('penelitian-pkm.index')
            ->with('status', 'Penelitian/PKM berhasil diajukan.');
    }

    public function show(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAnggotaTim($penelitianPkm);

        $penelitianPkm->load(
            'dosen',
            'laporanAkhir',
            'hilirisasi',
            'tahunAkademik'
        );

        return view(
            'penelitian-pkm.show',
            compact('penelitianPkm')
        );
    }

    public function edit(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanKetuaTim($penelitianPkm);

        $tahunAkademikList = TahunAkademik::all();

        $dosenId = Auth::user()->dosen->id;

        $dosenList = Dosen::where('id', '!=', $dosenId)->get();

        $penelitianPkm->load('dosen');

        return view(
            'penelitian-pkm.edit',
            compact(
                'penelitianPkm',
                'tahunAkademikList',
                'dosenList'
            )
        );
    }

    public function update(
        StorePenelitianPkmRequest $request,
        PenelitianPkm $penelitianPkm
    ) {
        $this->pastikanKetuaTim($penelitianPkm);

        // Update data utama penelitian/PKM.
        $penelitianPkm->update(
            $request->validated()
        );

        // Ambil anggota yang dipilih dari form.
        $anggotaIds = $request->input('anggota', []);

        // Ketua tetap adalah dosen yang sedang login.
        $ketuaId = Auth::user()->dosen->id;

        // Siapkan data pivot.
        $syncData = [];

        foreach ($anggotaIds as $dosenId) {
            $syncData[$dosenId] = [
                'peran' => 'Anggota',
            ];
        }

        // Pastikan dosen yang login tetap menjadi Ketua.
        $syncData[$ketuaId] = [
            'peran' => 'Ketua',
        ];

        // Sinkronisasi anggota tim.
        $penelitianPkm->dosen()->sync($syncData);

        return redirect()
            ->route('penelitian-pkm.index')
            ->with('status', 'Penelitian/PKM berhasil diperbarui.');
    }

    public function destroy(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanKetuaTim($penelitianPkm);
        $penelitianPkm->delete();

        return redirect()
            ->route('penelitian-pkm.index')
            ->with('status', 'Penelitian/PKM berhasil dihapus.');
    }

    /**
     * Kaprodi: lihat Penelitian/PKM
     * dari Prodi sendiri untuk diverifikasi.
     */
    public function verifikasiIndex()
    {
        $user = Auth::user();

        $isAdmin = $user->roles()
            ->where('nama_role', 'admin')
            ->exists();

        if ($isAdmin) {
            // Admin dapat melihat semua Penelitian/PKM.
            $penelitianList = PenelitianPkm::with(
                'dosen',
                'tahunAkademik'
            )
                ->latest()
                ->get();
        } else {
            // Kaprodi hanya melihat Penelitian/PKM
            // yang Ketua-nya berasal dari Prodi sendiri.
            $kaprodiPivot = $user->roles()
                ->where('nama_role', 'kaprodi')
                ->first()?->pivot;

            $prodiId = $kaprodiPivot?->prodi_id;

            if (! $prodiId) {
                abort(403, 'Akun Kaprodi belum memiliki Program Studi.');
            }

            $penelitianList = PenelitianPkm::with(
                'dosen',
                'tahunAkademik'
            )
                ->whereHas('dosen', function ($query) use ($prodiId) {
                    $query->where('dosen.prodi_id', $prodiId);
                })
                ->latest()
                ->get();
        }

        return view(
            'penelitian-pkm.verifikasi',
            compact('penelitianList')
        );
    }

    /**
     * Kaprodi: lihat detail Penelitian/PKM.
     */
    public function verifikasiShow(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAksesKaprodi($penelitianPkm);

        $penelitianPkm->load(
            'dosen',
            'tahunAkademik',
            'laporanAkhir',
            'hilirisasi'
        );

        return view(
            'penelitian-pkm.verifikasi-show',
            compact('penelitianPkm')
        );
    }

    /**
     * Kaprodi: setujui Penelitian/PKM.
     */
    public function verifikasiApprove(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAksesKaprodi($penelitianPkm);

        $penelitianPkm->update([
            'status' => 'disetujui',
        ]);

        return back()
            ->with('status', 'Penelitian/PKM telah disetujui.');
    }

    /**
     * Kaprodi: tolak Penelitian/PKM.
     */
    public function verifikasiReject(PenelitianPkm $penelitianPkm)
    {
        $this->pastikanAksesKaprodi($penelitianPkm);

        $penelitianPkm->update([
            'status' => 'ditolak',
        ]);

        return back()
            ->with('status', 'Penelitian/PKM telah ditolak.');
    }
}