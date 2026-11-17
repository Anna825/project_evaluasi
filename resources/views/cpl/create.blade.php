@extends('layouts.app')

@section('title', 'Tambah CPL - Evaluasi PBM')
@section('page-title', 'Tambah CPL')
@section('page-desc', 'Untuk kurikulum: ' . $kurikulum->nama)

@section('content')

    {{-- Tombol kembali --}}
    <div class="mb-6">
        <x-back-button href="{{ route('kurikulum.show', $kurikulum) }}" label="Kembali ke Kurikulum" />
    </div>

    {{-- Form CPL --}}
    <div class="w-full rounded-2xl border p-6 sm:p-8"
         style="background: var(--card); border-color: var(--border);">

        @if ($errors->any())
            <div role="alert" aria-live="assertive"
                 class="mb-6 px-4 py-4 rounded-xl border"
                 style="background: #fdecea; border-color: #e6a5a0; color: #9b2c2c;">
                <div class="flex items-start gap-3">
                    <svg class="shrink-0 mt-0.5" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path stroke-linecap="round" d="M12 8v4m0 4h.01"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold mb-1">CPL belum bisa disimpan.</p>
                        <p class="text-sm mb-2">Periksa kembali data yang kamu masukkan:</p>
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button"
                            onclick="this.closest('[role=alert]').remove()"
                            class="text-lg leading-none font-semibold hover:opacity-70"
                            aria-label="Tutup pemberitahuan">&times;</button>
                </div>
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
            <div class="mb-6">
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

                @error('kode')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Domain --}}
            <div class="mb-6">
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