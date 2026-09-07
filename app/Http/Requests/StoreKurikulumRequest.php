<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKurikulumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prodi_id' => ['required', 'exists:prodi,id'],
            'nama' => ['required', 'string', 'max:255'],
            'tahun_berlaku_mulai' => ['required', 'digits:4', 'integer', 'min:2000'],
            'status' => ['required', 'in:draft,aktif,nonaktif'],
        ];
    }
}