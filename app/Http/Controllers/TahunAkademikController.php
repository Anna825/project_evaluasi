<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTahunAkademikRequest;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\DB;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahunAkademik = TahunAkademik::with('semester')
            ->latest()
            ->get();

        return view('tahun-akademik.index', compact('tahunAkademik'));
    }

    public function create()
    {
        return view('tahun-akademik.create');
    }

    public function store(StoreTahunAkademikRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $tahunAkademik = TahunAkademik::create([
                'label' => $validated['label'],
            ]);

            // Semester Ganjil
            Semester::create([
                'tahun_akademik_id' => $tahunAkademik->id,
                'jenis' => 'ganjil',
                'tanggal_mulai' => $validated['ganjil_mulai'],
                'tanggal_selesai' => $validated['ganjil_selesai'],
            ]);

            // Semester Genap
            Semester::create([
                'tahun_akademik_id' => $tahunAkademik->id,
                'jenis' => 'genap',
                'tanggal_mulai' => $validated['genap_mulai'],
                'tanggal_selesai' => $validated['genap_selesai'],
            ]);
        });

        return redirect()
            ->route('tahun-akademik.index')
            ->with('status', 'Tahun akademik beserta semester Ganjil dan Genap berhasil ditambahkan.');
    }

    public function edit(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->load('semester');

        $ganjil = $tahunAkademik->semester
            ->firstWhere('jenis', 'ganjil');

        $genap = $tahunAkademik->semester
            ->firstWhere('jenis', 'genap');

        return view(
            'tahun-akademik.edit',
            compact('tahunAkademik', 'ganjil', 'genap')
        );
    }

    public function update(
        StoreTahunAkademikRequest $request,
        TahunAkademik $tahunAkademik
    ) {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $tahunAkademik) {
            $tahunAkademik->update([
                'label' => $validated['label'],
            ]);

            // Update atau buat Semester Ganjil
            Semester::updateOrCreate(
                [
                    'tahun_akademik_id' => $tahunAkademik->id,
                    'jenis' => 'ganjil',
                ],
                [
                    'tanggal_mulai' => $validated['ganjil_mulai'],
                    'tanggal_selesai' => $validated['ganjil_selesai'],
                ]
            );

            // Update atau buat Semester Genap
            Semester::updateOrCreate(
                [
                    'tahun_akademik_id' => $tahunAkademik->id,
                    'jenis' => 'genap',
                ],
                [
                    'tanggal_mulai' => $validated['genap_mulai'],
                    'tanggal_selesai' => $validated['genap_selesai'],
                ]
            );
        });

        return redirect()
            ->route('tahun-akademik.index')
            ->with('status', 'Tahun akademik dan periode semester berhasil diperbarui.');
    }

    public function destroy(TahunAkademik $tahunAkademik)
    {
        $sudahDigunakan = $tahunAkademik->semester()
            ->whereHas('kelas')
            ->exists();

        if ($sudahDigunakan) {
            return redirect()
                ->route('tahun-akademik.index')
                ->withErrors([
                    'tahun_akademik' => 'Tahun akademik tidak dapat dihapus karena sudah digunakan dalam data perkuliahan.',
                ]);
        }

        $tahunAkademik->delete();

        return redirect()
            ->route('tahun-akademik.index')
            ->with('status', 'Tahun akademik berhasil dihapus.');
    }
}