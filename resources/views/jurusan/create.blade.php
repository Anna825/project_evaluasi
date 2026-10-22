
@extends('layouts.app')

@section('title', 'Tambah Jurusan - Evaluasi PBM')
@section('page-title', 'Tambah Jurusan')
@section('page-desc', 'Tambahkan data jurusan baru.')

@section('content')
    @if ($errors->any())
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: #e5a3a3; color: #a13d3d;">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl rounded-2xl border p-6"
         style="background: var(--card); border-color: var(--border);">
        <form action="{{ route('jurusan.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="nama" class="block text-sm font-medium mb-2">
                    Nama Jurusan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama"
                       value="{{ old('nama') }}" required maxlength="255"
                       class="w-full rounded-xl border px-3 py-2 text-sm"
                       style="background: var(--card); border-color: var(--border);"
                       placeholder="Contoh: Teknik Elektro">
            </div>

            <div class="mb-5">
                <label for="kajur" class="block text-sm font-medium mb-2">
                    Nama Ketua Jurusan
                </label>
                <input type="text" id="kajur" name="kajur"
                       value="{{ old('kajur') }}" maxlength="255"
                       class="w-full rounded-xl border px-3 py-2 text-sm"
                       style="background: var(--card); border-color: var(--border);"
                       placeholder="Masukkan nama ketua jurusan (opsional)">
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('jurusan.index') }}"
                   class="px-4 py-2 rounded-xl text-sm border"
                   style="border-color: var(--border);">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
                        style="background: var(--primary);">
                    Simpan Jurusan
                </button>
            </div>
        </form>
    </div>
@endsection
