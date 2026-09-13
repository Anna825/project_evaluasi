<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prodi_id' => [
                'required',
                'exists:prodi,id',
            ],

            'kelas_mahasiswa_id' => [
                'required',
            ],

            'kelas_baru' => [
                'nullable',
                'string',
                'max:255',
                'required_if:kelas_mahasiswa_id,baru',
            ],

            'nim' => [
                'required',
                'string',
                'unique:mahasiswa,nim',
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'angkatan' => [
                'required',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 1),
            ],

            'status' => [
                'required',
                'in:aktif,cuti,lulus,DO',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.unique' => 'NIM ini sudah terdaftar di sistem.',
            'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',
            'kelas_mahasiswa_id.required' => 'Silakan pilih kelas atau pilih "Kelas belum tersedia".',
            'kelas_baru.required_if' => 'Nama kelas baru wajib diisi.',
        ];
    }
}