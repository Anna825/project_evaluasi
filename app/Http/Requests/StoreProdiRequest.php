<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('prodi', 'nama')->ignore($this->route('prodi')),
            ],
        ];
    }
}