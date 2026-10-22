@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah - Evaluasi PBM')
@section('page-title', 'Tambah Mata Kuliah')
@section('page-desc', 'Tambahkan mata kuliah ke dalam kurikulum yang dipilih.')

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

    <div class="mb-5">
        <a href="{{ route('kurikulum.show', $kurikulum) }}"
           class="text-sm hover:underline"
           style="color: var(--muted-foreground);">
            ← Kembali ke Detail Kurikulum
        </a>
    </div>

    <div class="max-w-2xl rounded-2xl border p-6"
         style="background: var(--card); border-color: var(--border);">

        <div class="mb-6 rounded-xl p-4"
             style="background: var(--secondary);">
            <p class="text-xs mb-1"
               style="color: var(--muted-foreground);">
                Kurikulum
            </p>
            <p class="font-semibold">
                {{ $kurikulum->nama }}
            </p>
            <p class="text-sm mt-1"
               style="color: var(--muted-foreground);">
                {{ $kurikulum->prodi->nama ?? '-' }}
            </p>
        </div>

        <form action="{{ route('kurikulum.mata-kuliah.store', $kurikulum) }}"
              method="POST">
            @csrf

            <div class="mb-5">
                <label for="kode" class="block text-sm font-medium mb-2">
                    Kode Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <input type="text" id="kode" name="kode"
                       value="{{ old('kode') }}"
                       required maxlength="50"
                       class="w-full rounded-xl border px-3 py-2 text-sm"
                       style="background: var(--card); border-color: var(--border);"
                       placeholder="Contoh: TII101">
            </div>

            <div class="mb-5">
                <label for="nama" class="block text-sm font-medium mb-2">
                    Nama Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama"
                       value="{{ old('nama') }}"
                       required maxlength="255"
                       class="w-full rounded-xl border px-3 py-2 text-sm"
                       style="background: var(--card); border-color: var(--border);"
                       placeholder="Contoh: Pemrograman Dasar">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label for="sks" class="block text-sm font-medium mb-2">
                        Jumlah SKS <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="sks" name="sks"
                           value="{{ old('sks') }}"
                           required min="1" max="10"
                           class="w-full rounded-xl border px-3 py-2 text-sm"
                           style="background: var(--card); border-color: var(--border);">
                </div>

                <div>
                    <label for="semester_ke" class="block text-sm font-medium mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select id="semester_ke" name="semester_ke" required
                            class="w-full rounded-xl border px-3 py-2 text-sm"
                            style="background: var(--card); border-color: var(--border);">
                        <option value="">Pilih Semester</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}"
                                @selected(old('semester_ke') == $i)>
                                Semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label for="jenis" class="block text-sm font-medium mb-2">
                    Jenis Mata Kuliah
                </label>
                <select id="jenis" name="jenis"
                        class="w-full rounded-xl border px-3 py-2 text-sm"
                        style="background: var(--card); border-color: var(--border);">
                    <option value="">Pilih Jenis Mata Kuliah</option>
                    <option value="Teori" @selected(old('jenis') === 'Teori')>
                        Teori
                    </option>
                    <option value="Praktek" @selected(old('jenis') === 'Praktek')>
                        Praktek
                    </option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('kurikulum.show', $kurikulum) }}"
                   class="px-4 py-2 rounded-xl text-sm border"
                   style="border-color: var(--border);">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
                        style="background: var(--primary);">
                    Simpan Mata Kuliah
                </button>
            </div>
        </form>
    </div>
@endsection