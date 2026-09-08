@extends('layouts.app')

@section('title', 'Tambah Kurikulum - Evaluasi PBM')
@section('page-title', 'Tambah Kurikulum')
@section('page-desc', 'Isi data kurikulum baru.')

@section('content')

    {{-- Header Bar: Breadcrumb & Tombol Kembali --}}
    {{-- Navigasi Kembali --}}
    <div class="mb-6">
        <a
            href="{{ route('kurikulum.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium hover:underline"
            style="color: var(--primary);"
        >
            <svg
                width="16"
                height="16"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
            </svg>

            Kembali ke Kurikulum
        </a>
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
                              d="M12 6v12M6 12h12" />

                    </svg>
                </div>

                <div>
                    <h2 class="font-serif-display text-lg font-bold">
                        Informasi Kurikulum
                    </h2>

                    <p class="text-sm mt-1"
                       style="color: var(--muted-foreground);">
                        Masukkan informasi kurikulum yang akan digunakan dalam sistem evaluasi PBM.
                    </p>
                </div>

            </div>

            <form method="POST"
                  action="{{ route('kurikulum.store') }}"
                  class="space-y-6">

                @csrf

                {{-- Program Studi --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Program Studi
                    </label>

                    <select
                        name="prodi_id"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none"
                        style="border-color: var(--border);"
                        required
                    >
                        <option value="">-- Pilih Program Studi --</option>

                        @foreach ($prodiList as $prodi)
                            <option
                                value="{{ $prodi->id }}"
                                {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}
                            >
                                {{ $prodi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Kurikulum --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Nama Kurikulum
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Kurikulum 2026"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none"
                        style="border-color: var(--border);"
                        required
                    >
                </div>

                {{-- Tahun Berlaku Mulai --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Tahun Berlaku Mulai
                    </label>

                    <input
                        type="number"
                        name="tahun_berlaku_mulai"
                        value="{{ old('tahun_berlaku_mulai') }}"
                        placeholder="Contoh: 2026"
                        min="1900"
                        max="2100"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none"
                        style="border-color: var(--border);"
                        required
                    >
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none"
                        style="border-color: var(--border);"
                        required
                    >
                        <option value="draft"
                            {{ old('status') == 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="aktif"
                            {{ old('status') == 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="nonaktif"
                            {{ old('status') == 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
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

                        Simpan Kurikulum
                    </button>

                    <a
                        href="{{ route('kurikulum.index') }}"
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