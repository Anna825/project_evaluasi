<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMahasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'prodi_id' => ['required', 'exists:prodi,id'],
            'nim' => ['required', 'string', Rule::unique('mahasiswa', 'nim')->ignore($this->route('mahasiswa'))],
            'nama' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:' . date('Y')],
            'ipk_terakhir' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'status' => ['required', 'in:aktif,cuti,lulus,DO'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.unique' => 'NIM ini sudah terdaftar di sistem.',
            'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',
        ];
    }
}