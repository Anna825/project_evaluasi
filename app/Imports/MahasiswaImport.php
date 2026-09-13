<?php

namespace App\Imports;

use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MahasiswaImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        $prodiNama = trim((string) ($row['program_studi'] ?? ''));
        $namaKelas = trim((string) ($row['kelas'] ?? ''));
        $angkatan = (int) $row['angkatan'];

        $prodi = Prodi::where('nama', $prodiNama)->first();

        if (!$prodi) {
            return null;
        }

        $kelasMahasiswa = KelasMahasiswa::firstOrCreate(
            [
                'prodi_id' => $prodi->id,
                'nama_kelas' => $namaKelas,
                'angkatan' => $angkatan,
            ],
            [
                'status_kelas' => 'aktif',
            ]
        );

        return new Mahasiswa([
            'prodi_id' => $prodi->id,
            'kelas_mahasiswa_id' => $kelasMahasiswa->id,
            'nim' => trim((string) $row['nim']),
            'nama' => trim((string) $row['nama']),
            'status' => strtolower(trim((string) ($row['status'] ?? 'aktif'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'nim' => [
                'required',
                'distinct',
                'unique:mahasiswa,nim',
            ],

            'nama' => [
                'required',
                'string',
            ],

            'program_studi' => [
                'required',
                'exists:prodi,nama',
            ],

            'kelas' => [
                'required',
                'string',
            ],

            'status' => [
                'required',
                function ($attribute, $value, $fail) {
                    $status = strtolower(trim((string) $value));

                    if (!in_array($status, ['aktif', 'cuti', 'lulus', 'do'])) {
                        $fail('Status mahasiswa harus Aktif, Cuti, Lulus, atau DO.');
                    }
                },
            ],

            'angkatan' => [
                'required',
                'integer',
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'nim.distinct' => 'Terdapat NIM duplikat di dalam file.',

            'nama.required' => 'Nama mahasiswa wajib diisi.',

            'program_studi.required' => 'Program Studi wajib diisi.',
            'program_studi.exists' => 'Nama Program Studi tidak ditemukan di sistem.',

            'kelas.required' => 'Kelas mahasiswa wajib diisi.',

            'status.required' => 'Status mahasiswa wajib diisi.',

            'angkatan.required' => 'Angkatan wajib diisi.',
            'angkatan.integer' => 'Angkatan harus berupa angka.',
        ];
    }
}