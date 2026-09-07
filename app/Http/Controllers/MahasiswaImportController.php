<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaImport;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaImportController extends Controller
{
    /**
     * Tampilkan form upload.
     */
    public function create()
    {
        $prodis = Prodi::orderBy('nama')->get();
        $totalMahasiswa = Mahasiswa::count();

        return view('mahasiswa.import', compact('prodis', 'totalMahasiswa'));
    }

    /**
     * Proses upload dan import file Excel.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls,csv'],
        ]);

        $import = new MahasiswaImport();
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();

        if ($failures->isEmpty()) {
            return redirect()->route('mahasiswa.index')
                ->with('status', 'Import berhasil! Semua data mahasiswa berhasil ditambahkan.');
        }

        return redirect()->route('mahasiswa.import.create')
            ->with('failures', $failures)
            ->with('status', 'Import selesai dengan beberapa baris gagal. Lihat detail di bawah.');
    }

    /**
     * Download template Excel kosong untuk diisi.
     */
    public function template()
    {
        $filePath = storage_path('app/private/template/template_mahasiswa.xlsx');
        
        return response()->download($filePath);
    }
}