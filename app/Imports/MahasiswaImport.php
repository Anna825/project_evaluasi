<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * @param array $row
     */
    public function model(array $row)
    {
        $prodi = Prodi::where('nama', trim($row['prodi']))->first();

        return new Mahasiswa([
            'prodi_id' => $prodi?->id,
            'nim' => $row['nim'],
            'nama' => $row['nama'],
            'angkatan' => $row['angkatan'],
            'ipk_terakhir' => $row['ipk_terakhir'] ?? null,
            'status' => $row['status'] ?? 'aktif',
        ]);
    }

    /**
     * Aturan validasi per baris.
     */
    public function rules(): array
    {
        return [
            'prodi' => ['required', 'exists:prodi,nama'],
            'nim' => ['required', 'distinct', 'unique:mahasiswa,nim'],
            'nama' => ['required', 'string'],
            'angkatan' => ['required', 'integer'],
            'ipk_terakhir' => ['nullable', 'numeric'],
            'status' => ['nullable', 'in:aktif,cuti,lulus,DO'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'prodi.exists' => 'Nama program studi tidak ditemukan di sistem.',
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'nim.distinct' => 'NIM duplikat di dalam file.',
        ];
    }
}