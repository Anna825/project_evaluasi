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
        // Saat edit, abaikan CPL yang sedang diperbarui.
        $cpl = $this->route('cpl');

        return [
            'kurikulum_id' => ['required', 'exists:kurikulum,id'],
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cpl', 'kode')->ignore($cpl?->id),
            ],
            'domain' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode CPL wajib diisi.',
            'kode.unique' => 'Kode CPL sudah digunakan. Silakan gunakan kode lain.',
            'kode.max' => 'Kode CPL maksimal 20 karakter.',
        ];
    }
}
