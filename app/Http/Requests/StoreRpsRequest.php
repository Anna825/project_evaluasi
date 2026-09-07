<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRpsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mata_kuliah_id' => ['required', 'exists:mata_kuliah,id'],
            'versi' => ['required', 'string', 'max:20'],
            'deskripsi_singkat' => ['nullable', 'string'],
            'tanggal_disusun' => ['required', 'date'],
        ];
    }
}