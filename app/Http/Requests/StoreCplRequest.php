<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCplRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kurikulum_id' => ['required', 'exists:kurikulum,id'],
            'kode' => ['required', 'string', 'max:20'],
            'domain' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ];
    }
}