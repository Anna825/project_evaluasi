@extends('layouts.app')

@section('title', 'Tambah Prodi - Evaluasi PBM')
@section('page-title', 'Tambah Program Studi')
@section('page-desc', 'Isi data program studi baru.')

@section('content')

    {{-- Header Bar: Breadcrumb & Tombol Kembali --}}
    <div class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('prodi.index') }}"
           class="inline-flex items-center gap-1.5 font-medium hover:underline"
           style="color: var(--primary);">

            <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>

            Kembali ke Program Studi
        </a>

        <span style="color: var(--muted-foreground);">/</span>

        <span style="color: var(--muted-foreground);">
            Tambah Program Studi
        </span>
    </div>

    {{-- Alert Error Validasi --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm"
             style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">

            <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <div>
                <p class="font-semibold mb-1">Terjadi Kesalahan:</p>

                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Utama --}}
    <div class="w-full">
        <div class="rounded-2xl border p-6 sm:p-8"
             style="background: var(--card); border-color: var(--border);">

            {{-- Header Form --}}
            <div class="flex items-start gap-4 mb-8">

                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                     style="background: var(--secondary); color: var(--primary);">

                    <svg width="26"
                         height="26"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 14l9-5-9-5-9 5 9 5z" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 14l6.16-3.422A12.083 12.083 0 0118 15.5c0 1.657-2.686 3-6 3s-6-1.343-6-3c0-1.008.69-1.914 1.84-2.922L12 14z" />
                    </svg>
                </div>

                <div>
                    <h2 class="font-serif-display text-lg font-bold">
                        Informasi Program Studi
                    </h2>

                    <p class="text-sm mt-1"
                       style="color: var(--muted-foreground);">
                        Masukkan informasi program studi yang akan ditambahkan ke dalam sistem.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('prodi.store') }}" class="space-y-6">
                @csrf

                {{-- Jurusan --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Jurusan
                    </label>

                    <select
                        name="jurusan_id"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none"
                        style="border-color: var(--border);"
                        required
                    >
                        <option value="">-- Pilih Jurusan --</option>

                        @foreach ($jurusanList as $jurusan)
                            <option
                                value="{{ $jurusan->id }}"
                                {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}
                            >
                                {{ $jurusan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Program Studi --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Nama Program Studi
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Teknologi Rekayasa Perangkat Lunak"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none"
                        style="border-color: var(--border);"
                        required
                    >
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 pt-2">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-medium text-white transition hover:opacity-90 shadow-sm"
                        style="background: var(--primary);"
                    >
                        <svg
                            width="18"
                            height="18"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        Simpan Program Studi
                    </button>

                    <a
                        href="{{ route('prodi.index') }}"
                        class="px-5 py-3 rounded-xl text-sm font-medium border transition hover:bg-gray-50"
                        style="border-color: var(--border); color: var(--muted-foreground);"
                    >
                        Batal
                    </a>

                </div>

            </form>
        </div>
    </div>

@endsection