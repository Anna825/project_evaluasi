<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaWorkbookImport;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MahasiswaImportController extends Controller
{
    public function create()
    {
        $totalMahasiswa = Mahasiswa::count();

        return view('mahasiswa.import', compact('totalMahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls,csv'],
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheetNames = $spreadsheet->getSheetNames();
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            $import = new MahasiswaWorkbookImport($sheetNames);

            Excel::import($import, $file);

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
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('mahasiswa.import.create')
                ->withInput()
                ->with(
                    'status',
                    'Import gagal diproses. Periksa format file Excel dan struktur setiap sheet.'
                );
        }
    }

    public function template()
    {
        $filePath = storage_path(
            'app/private/template/template_mahasiswa.xlsx'
        );

        if (! file_exists($filePath)) {
            abort(404, 'Template mahasiswa tidak ditemukan.');
        }

        return response()->download(
            $filePath,
            'template_mahasiswa.xlsx',
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }
}
