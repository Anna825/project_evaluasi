<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCpmkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mata_kuliah_id' => [
                'required',
                'integer',
                'exists:mata_kuliah,id',
            ],
            'kode' => [
                'required',
                'string',
                'max:20',
            ],
            'deskripsi' => [
                'required',
                'string',
            ],
            'cpl_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'cpl_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:cpl,id',
            ],
            'bobot' => [
                'required',
                'array',
            ],
            'bobot.*' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }
}