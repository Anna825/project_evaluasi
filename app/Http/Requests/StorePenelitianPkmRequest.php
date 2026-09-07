<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenelitianPkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:Penelitian,PKM'],
            'kategori_pendanaan' => ['nullable', 'string', 'max:255'],
            'tahun_akademik_id' => ['required', 'exists:tahun_akademik,id'],
        ];
    }
}