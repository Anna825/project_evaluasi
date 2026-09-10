@extends('layouts.app')

@section('title', 'Tambah CPL - Evaluasi PBM')
@section('page-title', 'Tambah CPL')
@section('page-desc', 'Untuk kurikulum: ' . $kurikulum->nama)

@section('content')

    {{-- Tombol kembali --}}
    <div class="mb-4 text-left">
        <a href="{{ route('kurikulum.show', $kurikulum) }}"
           class="text-sm underline hover:opacity-70"
           style="color: var(--muted-foreground);">
            ← Kembali ke Kurikulum
        </a>
    </div>

    {{-- Form CPL --}}
    <div class="w-full rounded-2xl border p-6 sm:p-8"
         style="background: var(--card); border-color: var(--border);">

        @if ($errors->any())
            <div class="mb-6 px-4 py-3 rounded-xl text-sm"
                 style="background: #fdecea; color: #a13d3d;">

                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach

            </div>
        @endif

        <div class="mb-6">
            <h2 class="font-serif-display text-lg">
                Informasi CPL
            </h2>

            <p class="text-sm mt-1"
               style="color: var(--muted-foreground);">
                Masukkan data Capaian Pembelajaran Lulusan untuk kurikulum ini.
            </p>
        </div>

        <form method="POST" action="{{ route('cpl.store') }}">
            @csrf

            <input type="hidden"
                   name="kurikulum_id"
                   value="{{ $kurikulum->id }}">

            {{-- Kode CPL --}}
            <div class="mb-5">
                <label class="block text-sm font-medium mb-1">
                    Kode CPL
                </label>

                <input
                    type="text"
                    name="kode"
                    value="{{ old('kode') }}"
                    placeholder="Contoh: CPL-01"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                    required
                >
            </div>

            {{-- Domain --}}
            <div class="mb-5">
                <label class="block text-sm font-medium mb-1">
                    Domain
                    <span class="font-normal"
                          style="color: var(--muted-foreground);">
                        (opsional)
                    </span>
                </label>

                <input
                    type="text"
                    name="domain"
                    value="{{ old('domain') }}"
                    placeholder="Contoh: Keterampilan Khusus"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                >
            </div>

            {{-- Deskripsi --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="5"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                    required
                >{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2">

                <button
                    type="submit"
                    class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
                    style="background: var(--primary);">
                    Simpan
                </button>

                <a
                    href="{{ route('kurikulum.show', $kurikulum) }}"
                    class="px-4 py-2 rounded-xl text-sm font-medium"
                    style="background: var(--secondary); color: var(--foreground);">
                    Batal
                </a>

            </div>

        </form>

    </div>

@endsection