<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTahunAkademikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tahunAkademik = $this->route('tahun_akademik');

        return [
            'label' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tahun_akademik', 'label')
                    ->ignore($tahunAkademik?->id),
            ],

            // Semester Ganjil
            'ganjil_mulai' => [
                'required',
                'date',
            ],

            'ganjil_selesai' => [
                'required',
                'date',
                'after:ganjil_mulai',
            ],

            // Semester Genap
            'genap_mulai' => [
                'required',
                'date',
            ],

            'genap_selesai' => [
                'required',
                'date',
                'after:genap_mulai',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Label tahun akademik wajib diisi.',
            'label.unique' => 'Tahun akademik tersebut sudah ada.',

            'ganjil_mulai.required' => 'Tanggal mulai semester ganjil wajib diisi.',
            'ganjil_selesai.required' => 'Tanggal selesai semester ganjil wajib diisi.',
            'ganjil_selesai.after' => 'Tanggal selesai semester ganjil harus setelah tanggal mulai.',

            'genap_mulai.required' => 'Tanggal mulai semester genap wajib diisi.',
            'genap_selesai.required' => 'Tanggal selesai semester genap wajib diisi.',
            'genap_selesai.after' => 'Tanggal selesai semester genap harus setelah tanggal mulai.',
        ];
    }
}