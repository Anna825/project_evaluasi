<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiDosenController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;

        $prestasiList = $dosen
            ->prestasi()
            ->with('tahunAkademik')
            ->latest()
            ->get();

        return view(
            'prestasi.dosen-index',
            compact('prestasiList')
        );
    }

    public function create()
    {
        $tahunAkademikList = TahunAkademik::all();

        return view(
            'prestasi.dosen-create',
            compact('tahunAkademikList')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],

            'tingkat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jenis' => [
                'nullable',
                'string',
                'max:255',
            ],

            'peringkat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tempat_pelaksanaan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tanggal_penerimaan' => [
                'nullable',
                'date',
            ],

            'tahun_akademik_id' => [
                'required',
                'exists:tahun_akademik,id',
            ],
        ]);

        $prestasi = Prestasi::create($validated);

        $dosen = Auth::user()->dosen;

        $prestasi->dosen()->attach($dosen->id);

        return redirect()
            ->route('prestasi-dosen.index')
            ->with(
                'status',
                'Prestasi berhasil ditambahkan.'
            );
    }

    public function show(Prestasi $prestasi)
    {
        $dosen = Auth::user()->dosen;

        if (! $prestasi->dosen()->whereKey($dosen->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }

        $prestasi->load([
            'dosen.prodi',
            'tahunAkademik',
        ]);

        return view(
            'prestasi.dosen-show',
            compact('prestasi')
        );
    }

    public function edit(Prestasi $prestasi)
    {
        $dosen = Auth::user()->dosen;

        if (! $prestasi->dosen()->whereKey($dosen->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }

        $tahunAkademikList = TahunAkademik::all();

        $prestasi->load([
            'tahunAkademik',
        ]);

        return view(
            'prestasi.dosen-edit',
            compact(
                'prestasi',
                'tahunAkademikList'
            )
        );
    }

    public function destroy(Prestasi $prestasi)
    {
        $dosen = Auth::user()->dosen;

        if (! $prestasi->dosen()->whereKey($dosen->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }

        // Lepaskan hubungan prestasi dengan dosen
        $prestasi->dosen()->detach($dosen->id);

        // Hapus data prestasi
        $prestasi->delete();

        return redirect()
            ->route('prestasi-dosen.index')
            ->with(
                'status',
                'Prestasi berhasil dihapus.'
            );
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $dosen = Auth::user()->dosen;

        if (! $prestasi->dosen()->whereKey($dosen->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke prestasi ini.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],

            'tingkat' => ['nullable', 'string', 'max:255'],

            'jenis' => ['nullable', 'string', 'max:255'],

            'peringkat' => ['nullable', 'string', 'max:255'],

            'tempat_pelaksanaan' => ['nullable', 'string', 'max:255'],

            'tanggal_penerimaan' => ['nullable', 'date'],

            'tahun_akademik_id' => [
                'required',
                'exists:tahun_akademik,id',
            ],
        ]);

        $prestasi->update($validated);

        return redirect()
            ->route('prestasi-dosen.show', $prestasi->id)
            ->with(
                'status',
                'Prestasi berhasil diperbarui.'
            );
    }
}