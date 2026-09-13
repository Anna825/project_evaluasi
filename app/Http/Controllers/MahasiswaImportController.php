<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaImport;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaImportController extends Controller
{
    /**
     * Tampilkan form upload.
     */
    public function create()
    {
        $totalMahasiswa = Mahasiswa::count();

        return view('mahasiswa.import', compact('totalMahasiswa'));
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
            return redirect()
                ->route('mahasiswa.index')
                ->with(
                    'status',
                    'Import berhasil! Semua data mahasiswa berhasil ditambahkan.'
                );
        }

        return redirect()
            ->route('mahasiswa.import.create')
            ->with('failures', $failures)
            ->with(
                'status',
                'Import selesai dengan beberapa baris gagal. Lihat detail di bawah.'
            );
    }

    /**
     * Download template Excel.
     */
    public function template()
    {
        $filePath = storage_path('app/private/template/template_mahasiswa.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'Template mahasiswa tidak ditemukan.');
        }

        return response()->download(
            $filePath,
            'template_mahasiswa.xlsx',
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }
}